<div
	class="question"
	name="aQuestion"
	data-evqust="<?php echo $id?>"
	onclick="addLikes(<?php echo $id?>)"
	><?php echo htmlspecialchars($content)?><img class="heart" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/heart.png" alt="Likez"/><span
		name="likes"
		class="counter"
		data-evqust="<?php echo $id?>"
		><?php echo $likes?></span
	></div
>