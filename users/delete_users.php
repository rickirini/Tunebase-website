<?php

      if ($_SERVER["REQUEST_METHOD"] != "POST") {
      echo "HTTP/1.0 405 Method Not Allowed <br />";
      echo "Allow: POST";
      die();
      }

      require_once('../config.inc.php');

        $user_id = $_POST['user_id'];

        //delete user's likes first
        $stmtdeletelikes = $dbh->prepare(" DELETE
                                 FROM like_song
                                 WHERE user_id = :userid");
        $stmtdeletelikes->bindParam(':userid', $user_id);
        $stmtdeletelikes->execute();


        //delete songs from playlist
        $stmtx = $dbh->prepare(" SELECT playlist_id
                                 FROM playlists
                                 WHERE user_id = :userid");
        $stmtx->bindParam(':userid', $user_id);
        $stmtx->execute();
        $countx = $stmtx->rowCount();

        if ($countx > 0) {
          foreach($stmtx as $row) {
          $playlist_id = $row['playlist_id'];

              //delete song from playlist
              $stmty = $dbh->prepare(" DELETE
                                       FROM playlist_song
                                       WHERE playlist_id = :playlist_id");
              $stmty->bindParam(':playlist_id', $playlist_id);
              $stmty->execute();
          }
        }

        //delete playlist
        $stmtz = $dbh->prepare(" DELETE
                                 FROM playlists
                                 WHERE user_id = :userid");
        $stmtz->bindParam(':userid', $user_id);
        $stmtz->execute();


        //delete songs of user
        $stmts = $dbh->prepare(" SELECT *
                                 FROM songs
                                 WHERE user_id = :userid");
        $stmts->bindParam(':userid', $user_id);
        $stmts->execute();
        $counts = $stmts->rowCount();

        if ($counts > 0) {
          foreach($stmts as $row) {
          $song_id = $row['song_id'];

              $qh = $dbh->prepare("SELECT distinct artists.name as artist, artists.artist_id as artist_id, songs.title as title, songs.year as year, albums.title as album, albums.album_id as album_id, genres.name as genre, genres.genre_id as genre_id, songs.song_id as song_id FROM songs INNER JOIN album_song ON songs.song_id = album_song.song_id INNER JOIN albums ON album_song.album_id = albums.album_id INNER JOIN artist_album ON albums.album_id = artist_album.album_id INNER JOIN artists ON artist_album.artist_id = artists.artist_id INNER JOIN artist_song ON artists.artist_id = artist_song.artist_id INNER JOIN song_genre ON songs.song_id = song_genre.song_id INNER JOIN genres ON song_genre.genre_id = genres.genre_id WHERE songs.song_id = ?");
            	$qh->execute(array($song_id));

            	$qh = $dbh->prepare("DELETE FROM artist_song WHERE song_id = ?");
            	$qh->execute(array($song_id));

            	$qh = $dbh->prepare("DELETE FROM album_song WHERE song_id = ?");
            	$qh->execute(array($song_id));

            	$qh = $dbh->prepare("DELETE FROM song_genre WHERE song_id = ?");
            	$qh->execute(array($song_id));

            	$qh = $dbh->prepare("DELETE FROM like_song WHERE song_id = ?");
            	$qh->execute(array($song_id));

            	$qh = $dbh->prepare("DELETE FROM songs WHERE song_id = ?");
            	$qh->execute(array($song_id));

            	$qh = $dbh->prepare("DELETE FROM playlist_song WHERE song_id = ?");
            	$qh->execute(array($song_id));

            	$qh = $dbh->prepare("SELECT * FROM album_song WHERE album_id = ?");
            	$qh->execute(array($album_id));
            	$count = $qh->rowCount();
            	if ($count < 1) {
            			$qh = $dbh->prepare("DELETE FROM artist_album WHERE album_id = ?");
            			$qh->execute(array($album_id));
            			$qh = $dbh->prepare("DELETE FROM albums WHERE album_id = ?");
            			$qh->execute(array($album_id));
            	}

            	$qh = $dbh->prepare("SELECT * FROM artist_song WHERE artist_id = ?");
            	$qh->execute(array($artist_id));
            	$count = $qh->rowCount();
            	if ($count < 1) {
            			$qh = $dbh->prepare("DELETE FROM artists WHERE artist_id = ?");
            			$qh->execute(array($artist_id));
            	}
          }
        }

        //delete user
        $stmt = $dbh->prepare(" DELETE
                                FROM users
                                WHERE user_id = :userid");

        $stmt->bindParam(':userid', $user_id);
        $stmt->execute();


    header("Location: ../music/index.php");

?>
