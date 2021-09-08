<?php require('includes/config.php'); 

$stmt = $db->prepare('SELECT topicid,topicName FROM topics WHERE topicSlug = :topicSlug');
$stmt->execute(array(':topicSlug' => $_GET['id']));
$row = $stmt->fetch();

//if post does not exists redirect user.
if($row['topicid'] == ''){
    header('Location: ./');
    exit;
}


?>

<?php include("head.php");  ?>

	<title><?php echo $row['topicName'];?>- the Blog Site</title>
	<?php include("header.php");  ?>
<div class="content">
 

<p>Blog Topics:- <?php echo $row['topicName'];?></p>
        <hr>
       

        <?php    
        try {

            $stmt = $db->prepare('
                SELECT 
                    blogs.articleid, blogs.articleTitle, blogs.articleSlug, blogs.articleDescription, blogs.articleDate 
                FROM 
                    blogs,
                    topic_links
                WHERE
                     blogs.articleid =  topic_links.articleid
                     AND  topic_links.topicid = :topicid
                ORDER BY 
                    articleid DESC
                ');
            $stmt->execute(array(':topicid' => $row['topicid']));
            while($row = $stmt->fetch()){
                
                echo '<div>';
                    echo '<h1><a href="../'.$row['articleSlug'].'">'.$row['articleTitle'].'</a></h1>';
                    echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['articleDate'])).' in ';

                        $stmt2 = $db->prepare('SELECT topicName, topicSlug   FROM topics, topic_links WHERE topics.topicid = topic_links.topicid AND topic_links.articleid = :articleid');
                        $stmt2->execute(array(':articleid' => $row['articleid']));

                        $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                        $links = array();
                        foreach ($catRow as $cat)
                        {
                            $links[] = "<a href='".$cat['topicSlug']."'>".$cat['topicName']."</a>";
                        }
                        echo implode(", ", $links);

                    echo '</p>';
                    echo '<p>'.$row['articleDescription'].'</p>';                
                
                     echo '<p><button class="readbtn"><a href="../'.$row['articleSlug'].'">Read More</a></button></p>';   

                echo '</div>';

            }

        } catch(PDOException $e) {
            echo $e->getMessage();
        }

        ?>
</div>
<?php include("sidebar.php");  ?>

<?php include("footer.php");  ?> 