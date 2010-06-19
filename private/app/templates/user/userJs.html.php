<?php if ($this->isAuth()) { ?>
<script type="text/javascript">
$(document).ready(function() {
	user.init(
		<?php echo $this->getUid(); ?>,
		'<?php echo $this->getUsername(); ?>',
		'<?php echo $this->getRealname(); ?>'
	);
});
</script>
<?php } ?>