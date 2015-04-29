<?php
require_once ROOT_PATH.'/modules/phpQuery/phpQuery/phpQuery.php';
class Modules_Base_Models_NSF 
{
    private $topic = "http://forum.nasaspaceflight.com/index.php?topic=36559";
    private $grid;
    private $posts;
    private $users;
    private $overrides;

    public function getGrid()
    {
        if (!$this->grid) {
            $this->loadGrid();
        }
        return $this->grid;
    }

    public function refreshGrid($scrape_forum = false)
    {
        $this->prepareGrid();
        $this->fillFromForum($scrape_forum);
        $this->saveGrid();
    }

    public function addChoice($row, $column, $name, $link, $date, $post_id)
    {
        if (!$this->grid[$row][$column]['taken']) {
            $this->grid[$row][$column]['taken'] = true;
            $this->grid[$row][$column]['name'] = $name;
            $this->grid[$row][$column]['link'] = $link; 
            $this->grid[$row][$column]['date'] = $date;
            $this->grid[$row][$column]['post_id'] = $post_id;

            if (!isset($this->users[$name])) {
                $this->users[$name] = array();
            }

            $this->users[$name]['square'] = "$row-$column";

            if (!isset($this->users[$name]['posts'])) {
                $this->users[$name]['posts'] = array();
            }

            $this->users[$name]['posts'][$post_id.$row.$column] = array(
                    'square' => "$row-$column",
                    'info' => 'Vote Counted',
                    'name' => $name,
                    'link' => $link, 
                    'date' => $date,
                    'counter_link' => $counter_link,
                    'post_id' => $post_id,
            );
        }
    }

    public function addFailure($reason, $row, $column, $name, $link, $date, $post_id, $counter_link = null)
    {
        $this->grid[$row][$column]['failures'][] = array(
                'reason' => $reason,
                'name' => $name,
                'link' => $link, 
                'date' => $date,
                'counter_link' => $counter_link,
                'post_id' => $post_id,
                );

        if (!isset($this->users[$name])) {
            $this->users[$name] = array();
        }

        if (!isset($this->users[$name]['posts'])) {
            $this->users[$name]['posts'] = array();
        }

        $this->users[$name]['posts'][$post_id.$row.$column] = array(
                'square' => "$row-$column",
                'info' => $reason,
                'name' => $name,
                'link' => $link, 
                'date' => $date,
                'counter_link' => $counter_link,
                'post_id' => $post_id,
                );
    }

    public function saveGrid()
    {
        if (!file_exists(ROOT_PATH."/var/data")) {
            mkdir(ROOT_PATH."/var/data");
        }
        $fp = fopen(ROOT_PATH."/var/data/grid.dat", 'w');
        
        $grid = json_encode($this->grid);
        
        fwrite($fp, $grid);
        fclose($fp);

        $fu = fopen(ROOT_PATH."/var/data/users.dat", 'w');
        
        $users = json_encode($this->users);
        
        fwrite($fu, $users);
        fclose($fu);
    }

    public function savePosts()
    {
        if (!file_exists(ROOT_PATH."/var/data")) {
            mkdir(ROOT_PATH."/var/data");
        }
        $fp = fopen(ROOT_PATH."/var/data/posts.dat", 'w');
        
        $posts = json_encode($this->posts);
        
        fwrite($fp, $posts);
        fclose($fp);
    }

    protected function getPosts($refresh = false)
    {
        $this->loadPosts();
        if (count($this->posts) == 0 || $refresh) {
            phpQuery::newDocumentFileHTML($this->topic);
            $pages = intval(pq('.navPages')->elements[2]->textContent);
            $posts = $this->postArray(); 
            for ($page = 1; $page < $pages ; $page++) {
                phpQuery::newDocumentFileHTML($this->topic.".".($page*20));
                foreach ($this->postArray() as $newpost) {
                    $posts[] = $newpost;
                }
            }
            $this->posts = $posts;
            $this->savePosts();
        }
        return $this->posts;
    }

    // Builds an array of post data from the current
    // PHPQuery Document. A bit of silli statefulness
    protected function postArray()
    {
        $posts = array();
        pq('.bbc_standard_quote')->remove();
        pq('.quoteheader')->remove();
        pq('.quotefooter')->remove();
        pq('.smiley')->remove();
        pq('.bbc_link')->remove();
        foreach (pq('.post_wrapper') as $htmlpost) {
            $poster = trim(pq($htmlpost)->find('.poster a')->text());

            $date = pq($htmlpost)->find('.keyinfo .smalltext')->text();
            $date = preg_replace('/^[^:]*: /','',$date);
            $date = preg_replace('/M.*$/','M',$date);

            $link = pq($htmlpost)->find('.keyinfo a')->attr('href');
            $link = preg_replace('/PHPSESSID[^&]+&/','',$link);

            $id = preg_replace('/^[^#]*#/', '', $link); 

            $post = pq($htmlpost)->find('.post .inner')->html();
            $modified = strlen(trim(pq($htmlpost)->find('.moderatorbar .modified')->text())) > 0;

            $posts[] = array(
                    'poster' => utf8_encode($poster),
                    'date' => utf8_encode($date),
                    'link' => utf8_encode($link),
                    'post' => utf8_encode($post),
                    'modified' => utf8_encode($modified),
                    'id' => $id,
                    );
        }
        return $posts;
    }

    public function getUsers()
    {
        if (!$this->users) {
            if (!file_exists(ROOT_PATH."/var/data/users.dat")) {
                $this->prepareGrid();
                $this->users = array();
                return;
            } else {
                $file = file_get_contents(ROOT_PATH."/var/data/users.dat");
                $this->users = json_decode($file, true);
            }
        }
        return $this->users;
    }

    // Creates an empty grid.
    protected function prepareGrid()
    {
        $grid = array_flip(str_split('ABCDEFGHIJKLMNOPQRSTUVW'));
        foreach ($grid as $letter=>$row) {
            $grid[$letter] = array();
            for ($square = 1; $square <= 43; $square++) {
                $grid[$letter][$square] = array(
                        'taken' => false,
                        'name' => null,
                        'link' => null, 
                        'date' => null,
                        'failures' => array(),
                        'post_id' => null,
                        );
            }
        }

        $this->grid = $grid;
    }

    // Fills grid with saved data
    protected function loadGrid()
    {
        if (!file_exists(ROOT_PATH."/var/data/grid.dat")) {
            $this->prepareGrid();
            return;
        }
        $file = file_get_contents(ROOT_PATH."/var/data/grid.dat");
        
        $this->grid = json_decode($file, true);
    }

    // Fills post array with saved posts
    protected function loadPosts()
    {
        if (!file_exists(ROOT_PATH."/var/data/posts.dat")) {
            return array();
        }
        $file = file_get_contents(ROOT_PATH."/var/data/posts.dat");

        $this->posts = json_decode($file, true);
    }

    // Fills grid with filtered choices
    protected function fillFromForum($refresh = false)
    {
        $pattern = '/(^|[^a-zA-Z])([a-wA-W][^a-zA-W0-9]{0,3}[0-9]{1,2}?)([^a-zA-Z0-9]|$)/';
        $posts = $this->getPosts($refresh);
        $choices = array();

        foreach ($posts as $post) {
            $matches = array();
            if (preg_match_all($pattern, $post['post'],$matches)) {
                foreach ($matches[2] as $choice) {
                    $pick = strtoupper(preg_replace('/[^a-zA-Z0-9]/','',$choice));
                    $letter = preg_replace('/[^A-W]/','',$pick);
                    $number = intval(preg_replace('/[^0-9]/','',$pick));
                    if ($number < 44 && $number > 0) {
                        if (!$this->override($post['id'], $pick)) {
                            if (!array_key_exists($pick, $choices)) {
                                if (!array_search($post['poster'], $choices)) {
                                    if (!$post['modified']) {
                                        $choices[$pick] = $post['poster'];
                                        $this->addChoice(
                                                $letter, 
                                                $number, 
                                                $post['poster'], 
                                                $post['link'], 
                                                $post['date'],
                                                $post['id']
                                                );
                                    } else {
                                        // Post has been edited
                                        $this->addFailure(
                                                "Post Edited",
                                                $letter,
                                                $number,
                                                $post['poster'],
                                                $post['link'],
                                                $post['date'],
                                                $post['id']
                                                );
                                    }
                                } else {
                                    // Poster has already chosen
                                    $id = array_search($post['poster'], $choices);
                                    $row = preg_replace('/[^A-W]/','',$id);
                                    $col = intval(preg_replace('/[^0-9]/','',$id));

                                    $this->addFailure(
                                            "User has previous choice",
                                            $letter,
                                            $number,
                                            $post['poster'],
                                            $post['link'],
                                            $post['date'],
                                            $post['id'],
                                            $this->grid[$row][$col]['link']
                                            );
                                }
                            } else {
                                // This square has already been chosen
                                if ($post['poster'] !== $this->grid[$letter][$number]['name']) {
                                    $this->addFailure(
                                            "Square taken",
                                            $letter,
                                            $number,
                                            $post['poster'],
                                            $post['link'],
                                            $post['date'],
                                            $post['id']
                                            );
                                }
                            }
                        } else {
                            // Not a vote
                            if ($post['poster'] !== $this->grid[$letter][$number]['name']) {
                                $this->addFailure(
                                        "Not a vote",
                                        $letter,
                                        $number,
                                        $post['poster'],
                                        $post['link'],
                                        $post['date'],
                                        $post['id']
                                        );
                            }
                        }
                    }
                }
            } else {
//                echo "<p>"
//                echo "NO VOTE";
//                echo "<a href='{$post['link']}' target='_blank'>LINK</a>";
//                echo htmlspecialchars($post['post']);
//                echo "</p>";
            }
        }
        $this->saveGrid();
    }

    public function addOverride($id, $pick) 
    {
        if (!file_exists(ROOT_PATH."/var/data")) {
            mkdir(ROOT_PATH."/var/data");
        }
        
        if (!$this->override($id, $pick)) {
            
            $this->overrides[] = array('id'=>$id, 'pick'=>$pick);
            
            $fp = fopen(ROOT_PATH."/var/data/overrides.dat", 'w');
            fwrite($fp, json_encode($this->overrides));
            fclose($fp);
        }
    }

    public function removeOverride($id, $pick) 
    {
        if (!$this->overrides) {
            if (!file_exists(ROOT_PATH."/var/data/overrides.dat")) {
                $this->overrides = array();
            } else {
                $file = file_get_contents(ROOT_PATH."/var/data/overrides.dat");
                $this->overrides = json_decode($file, true);
            }
        }

        $id_to_delete = null;
        foreach ($this->overrides as $index=>$override) {
            if ($override['id'] == $id && $override['pick'] == $pick) {
                $id_to_delete = $index;
            }
        }
        
        if ($id_to_delete !== null) {
            unset($this->overrides[$id_to_delete]);
            $this->overrides = array_values($this->overrides);
            $fp = fopen(ROOT_PATH."/var/data/overrides.dat", 'w');
            fwrite($fp, json_encode($this->overrides));
            fclose($fp);
        }
    }

    public function override($id, $pick) 
    {
        if (!$this->overrides) {
            if (!file_exists(ROOT_PATH."/var/data/overrides.dat")) {
                $this->overrides = array();
            } else {
                $file = file_get_contents(ROOT_PATH."/var/data/overrides.dat");
                $this->overrides = json_decode($file, true);
            }
        }

        foreach ($this->overrides as $override) {
            if ($override['id'] == $id && $override['pick'] == $pick) {
                return true;
            }
        }

        return false;
    }
}
