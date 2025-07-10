<div
	class="<?php if ($questionSource > 0) echo "question1"; else echo "question"; ?><?php if ($selected) echo " selected"?>"
	name="aQuestion"
	data-evqust="<?php echo $id?>"
	onclick="selectQuestion(<?php echo $id?>)"
	><span
		name="questionContent"
		data-evqust="<?php echo $id?>"
		><?php if (isset($myResponse)) { if ($responseNumber == 1) { echo htmlspecialchars($response1); } if ($responseNumber == 2) { echo htmlspecialchars($response2); } if ($responseNumber == 3) { echo htmlspecialchars($response3); } } else { echo htmlspecialchars($content); }?></span
	><img class="heart" tabindex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/heart.png" alt="Likez" onclick="addLikes(<?php echo $id?>);event.stopPropagation();"/><span
		name="likes"
		class="counter"
		data-evqust="<?php echo $id?>"
		><?php echo $likes?></span
	><?php if ((isset($response1) || isset($response2) || isset($response3)) && !isset($myResponse)) { echo '<br/><div style="display:inline;float:right">'; if (isset($response1)) { echo '<span class="response" onclick="sendResponse(' . $idQuestion . ', ' . $response . ',1,1)">' . htmlspecialchars($response1) . '</span>'; } if (isset($response2)) { echo '<span class="response" onclick="sendResponse(' . $idQuestion . ', ' . $response . ',2,1)">' . htmlspecialchars($response2) . '</span>'; } if (isset($response3)) { echo '<span class="response" onclick="sendResponse(' . $idQuestion . ', ' . $response . ',3,1)">' . htmlspecialchars($response3) . '</span>'; } echo "</div>"; } ?><br/><img class="trash" tabIndex="-1" draggable="false" data-evqust="<?php echo $id?>" src="../images/trash.png" alt="Supprimer" onclick="removeQuestion(<?php echo $id?>)"/></div
>
