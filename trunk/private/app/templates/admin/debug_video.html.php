<form action="" method="get">
	<input name="q" value="<?php echo request::get("q"); ?>"></input>
	<button type="submit">go</button>
</form>
<a href="/admin/debug_video?truncate=1">truncate</a>

<table>
	<tr>
		<td>id</td>
		<td>name</td>
		<td>parsed_artist</td>
		<td>parsed_title</td>
		<td>mb_artist</td>
		<td>mb_title</td>
		<td>global</td>
		<td>artist</td>
		<td>title</td>
		<td>avg calc</td>
		<td>avg db</td>
	</tr>
<?php if ($this->videos) { 
	foreach ($this->videos as $video) {
		/* @var $video video */
		$video->addToMusicBrainzCue(); ?>
	<tr>
		<td><?php echo $video->getId(); ?></td>
		<td><?php echo $video->getName(); ?></td>
		<td><?php echo $video->getMeta("parsed_artist"); ?></td>
		<td><?php echo $video->getMeta("parsed_title"); ?></td>
		<td><?php echo $video->getMeta("mb_artist"); ?></td>
		<td><?php echo $video->getMeta("mb_title"); ?></td>
		<?php
		similar_text(strtolower($video->getName()), strtolower($video->getMeta("mb_artist") . " " . $video->getMeta("mb_title")), $global_perc);
		similar_text(strtolower($video->getMeta("parsed_artist")), strtolower($video->getMeta("mb_artist")), $artist_perc);
		similar_text(strtolower($video->getMeta("parsed_title")), strtolower($video->getMeta("mb_title")), $title_perc);
		?>
		<td><?php echo perc($global_perc); ?></td>
		<td><?php echo perc($artist_perc); ?></td>
		<td><?php echo perc($title_perc); ?></td>
		<td><?php echo perc(array_sum(array($global_perc, $artist_perc * 2, $title_perc)) / 4); ?></td>
		<td><?php echo $video->getMeta("mb_similar"); ?></td>
	</tr>
	<?php }
} ?>
</table>
<a target="_blank" href="http://localhost/video/runMusicBrainzCue/debug?cue=<?php echo urlencode(serialize(musicbrainz::$cue)); ?>">run cue</a>

<?php 

function perc($perc) {
	if ($perc == 100) {
		$color = "00FF00";
	} else if ($perc >= 90) {
		$color = "6DFF00";
	} else if ($perc >= 80) {
		$color = "CDFF00";
	} else if ($perc >= 70) {
		$color = "FFD100";
	} else if ($perc >= 60) {
		$color = "FF8A00";
	} else {
		$color = "FF0000";
	}
	return "<span style=\"font-size: 15px; font-weight: bold; color: black; background-color: #$color\">".(int) $perc ."</span>";
	
	
}

?>