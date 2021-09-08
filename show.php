<?php require('includes/config.php'); 

$stmt = $db->prepare('SELECT articleid,articleDescription,articleTitle, articleContent, articleDate, articleTags FROM blogs WHERE articleid = :articleid');
$stmt->execute(array(':articleid' => $_GET['id']));
$row = $stmt->fetch();

//if post does not exists redirect user.
if($row['articleid'] == ''){
    header('Location: ./index.php');
    exit;
}
?>

<?php include("head.php");  ?>

    <title><?php echo $row['articleTitle'];?>-The Blog Site</title>
  <meta name="description" content="<?php echo $row['articleDescription'];?>">    
<meta name="keywords" content="Article Keywords">

<?php include("header.php");  ?>
<div class="container">
<div class="content">


<?php
            echo '<div>';
                echo '<h1>'.$row['articleTitle'].'</h1>';

                echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['articleDate'])).' under the topic:';

                $stmt2 = $db->prepare('SELECT topicName, topicSlug   FROM topics, topic_links WHERE topics.topicid = topic_links.topicid AND topic_links.articleid = :articleid');
                $stmt2->execute(array(':articleid' => $row['articleid']));
                
                $catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $links = array();
                foreach ($catRow as $cat){
                     $links[] = "<a href='topics/".$cat['topicSlug']."'>".$cat['topicName']."</a>";
                }
                echo implode(", ", $links);
                
                echo '</p>';
                  echo '<hr>';
                
                echo '<p>'.$row['articleContent'].'</p>';    
                echo '<p>Tagged as: ';
                $links = array();
                $parts = explode(',', $row['articleTags']);
                foreach ($parts as $tags)
                {
                 $links[] = "<a href='".$tags."'>".$tags."</a>";
                }
                echo implode(", ", $links);
                echo '</p>';
            echo '</div>';
        ?>
       
   </div>
   <?php //sidebar content 
include("sidebar.php");  ?>

     </div>
<?php include("footer.php");  ?> 