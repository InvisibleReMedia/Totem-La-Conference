<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>
		</title>
		<style>
			body { } /* remember to set the width in size or % */ .center-vertically { margin:auto; padding:10px; } .padding-center-vertically { padding : 20px 0; } .line-height-center-vertically { line-height : 30px; height: 30px; } .multiple-lines-center-vertically { display:inline-block; vertical-align : middle; } .absolute-center { position: absolute; top:50%; left:50%; transform : translate(-50%,-50%); } .center-horizontally { display: block; margin: auto 0; } .text-center { text-align : center; } .left-horizontally { float : left; } .right-horizontally { float : right; } .clearfix { overflow : auto; } .flex-row : { display:flex; flex-direction:row; border:1px solid black; width:100% }
		</style>

	</head>
	<body>
		<div style="display:inline-flex;height:100px;width:100%">
			<div style="width:11%;border:1px solid black">
				&nbsp;
			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=first&lines_per_page=<?php echo $lines_per_page?>">
						&lt;&lt;
					</a>

				</div>

			</div>
			<div style="width:5%;border:1px solid black">
				&nbsp;
			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=&num_page=<?echo ($num_page-1)?>&lines_per_page=<?php echo $lines_per_page?>">
						&lt;
					</a>

				</div>

			</div>
			<div style="width:5%;border:1px solid black">
				&nbsp;
			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=&num_page=<?echo ($num_page-3)?>&lines_per_page=<?php echo $lines_per_page?>">
< ?echo ($num_page-3)?>
					</a>

				</div>

			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=&num_page=<?echo ($num_page-2)?>&lines_per_page=<?php echo $lines_per_page?>">
< ?echo ($num_page-2)?>
					</a>

				</div>

			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=&num_page=<?echo ($num_page-1)?>&lines_per_page=<?php echo $lines_per_page?>">
< ?echo ($num_page-1)?>
					</a>

				</div>

			</div>
			<div style="width:8%;border:1px solid black">
			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=&num_page=<?echo ($num_page+1)?>&lines_per_page=<?php echo $lines_per_page?>">
< ?echo ($num_page+1)?>
					</a>

				</div>

			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=&num_page=<?echo ($num_page+2)?>&lines_per_page=<?php echo $lines_per_page?>">
< ?echo ($num_page+2)?>
					</a>

				</div>

			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=&num_page=<?echo ($num_page+3)?>&lines_per_page=<?php echo $lines_per_page?>">
< ?echo ($num_page+3)?>
					</a>

				</div>

			</div>
			<div style="width:5%;border:1px solid black">
				&nbsp;
			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=&num_page=<?echo ($num_page+1)?>&lines_per_page=<?php echo $lines_per_page?>">
						&gt;
					</a>

				</div>

			</div>
			<div style="width:5%;border:1px solid black">
				&nbsp;
			</div>
			<div style="width:5%;border:1px solid black">
				<div style="line-height:98px;height:98px;text-align:center">
					<a href="pager.php?option=last&lines_per_page=<?php echo $lines_per_page?>">
						&gt;&gt;
					</a>

				</div>

			</div>
			<div style="width:11%;border:1px solid black">
				&nbsp;
			</div>

		</div>

	</body>

</html>