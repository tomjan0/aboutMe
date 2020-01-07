<?php
$sql = "SELECT autor, data, tresc FROM komentarze WHERE artykul_id='$articleId' ORDER BY data DESC";
$result = $db->query($sql);
$comments = $result->fetch_fields();
while ($row = $result->fetch_array()) {
  echo "<div><p class='comment-content'>" . $row['tresc'] . "</p><p class='comment-info'>~" . $row['autor'] . " <span>("
    . $row['data'] . ")</span></p></div>";
}