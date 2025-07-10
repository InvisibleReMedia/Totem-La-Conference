<div name="aQuestion" style="height:100%;border:1px solid black;border-radius:12px;background-color:#8989FF;transition:transform 1s" data-evqust="<?php echo $id?>">
	<article class="flex-row" style="width:100%;height:100%">
		<div style="height:90%;">
			<div style="display:inline-flex;width:100%">
				<div class="ql-editor ql-print" style="width:100%;margin-top:-50px;margin-bottom:-20px">
					<?php echo $content ?>
				</div>

			</div>

		</div>
		<div style="height:10%;border-top:1px solid black">
			<div style="display:inline-flex;height:30px;width:100%">
				<div style="width:65%;">
					&nbsp;
				</div>
				<div style="width:15%;border-left:1px solid black;z-index:1">
					<img tabindex="-1" draggable="false" noselection="true" style="border-radius:50%;cursor:pointer;max-height:25px;width:auto" data-evqust="<?php echo $id ?>" src="like-transparent.png" onmousedown="this.src='like-transparent-down.png'" onmouseup="this.src='like-transparent.png'" onmouseleave="this.src='like-transparent.png'" onclick="addLikes(this.dataset.evqust)"/>
				</div>
				<div style="width:10%;">
					<span name="likes" data-evqust="<?php echo $id ?>"><?php echo $likes ?></span>

				</div>
				<div style="width:10%;"></div>

			</div>

		</div>

	</article>

</div>