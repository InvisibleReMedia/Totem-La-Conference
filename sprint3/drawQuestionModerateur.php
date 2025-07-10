<div
	class="<?php if ($questionSource > 0) echo "question1"; else echo "question"; ?><?php if ($selected) echo " selected"?>"
	name="aQuestion"
	data-evqust="<?php echo $id?>"
	><input type="checkbox" name="moderated" data-evqust="<?php echo $id?>" checked="false"/><em
		style="color:red"
		><?php if ($moderated) echo "VISIBLE"; else echo "HIDDEN";?></em
	><br/><span
		name="questionContent"
		data-evqust="<?php echo $id?>"
		contenteditable="true"
		><?php if (isset($myResponse)) { if ($responseNumber == 1) { echo htmlspecialchars($response1); } if ($responseNumber == 2) { echo htmlspecialchars($response2); } if ($responseNumber == 3) { echo htmlspecialchars($response3); } } else { echo htmlspecialchars($content); }?></span
	><img class="heart" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/heart.png" alt="Likez" onclick="addLikes(<?php echo $id?>)"/><span
		name="likes"
		class="counter"
		data-evqust="<?php echo $id?>"
		><?php echo $likes?></span
	><?php if ((isset($response1) || isset($response2) || isset($response3)) && !isset($myResponse)) { echo '<br/><div style="display:inline;float:right">'; if (isset($response1)) { echo '<span class="response">' . htmlspecialchars($response1) . '</span>'; } if (isset($response2)) { echo '<span class="response">' . htmlspecialchars($response2) . '</span>'; } if (isset($response3)) { echo '<span class="response">' . htmlspecialchars($response3) . '</span>'; } echo "</div>"; } ?><br/><img class="trash" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/trash.png" alt="Supprimer" onclick="removeQuestion(<?php echo $id?>)"/><?php if (!isset($myResponse)) { echo '<img class="modify" tabIndex="-1" draggable="false" data-evqust="' . $id . '" src="images/modify.png" alt="Modifier" onclick="modifyQuestion(' . $id . ')"/>'; } ?><img class="push" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="images/push.png" alt="Push" onclick="pushQuestion(<?php echo $id?>)"/></div
>