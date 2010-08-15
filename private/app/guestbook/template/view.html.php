<?php

		// Include JS library:
		// $this->requireJsLibrary('jquery', '1.4');
		
		// TODO: it would be neat if we could do something like:
		// <ajde:include route="home/menu.html" />
		// <ajde:form action="">
		//		<ajde:input type="" name="" />
		// </ajde:form>
?>

<?php
echo $this->includeModule("guestbook/menu.html");
?>

<p>
Hello world aje!
</p>