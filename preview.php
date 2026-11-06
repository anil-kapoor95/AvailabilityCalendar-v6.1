<!DOCTYPE html>
<html>
	<head>
		<title>Availability Calendar Script - Preview</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<head>
	<body>
		<?php
		if (isset($_GET['cid']))
		{
			if ((int) $_GET['cid'] > 0)
			{
				$style = "width: 600px; height: 450px";
				if (isset($_GET['view']))
				{
					switch ($_GET['view'])
					{
						case 3:
							$style = "max-width: 800px; height: 650px";
							break;
						case 6:
							$style = "max-width: 800px; height: 650px";
							break;
						case 12:
							$style = "max-width: 800px; height: 650px";
							break;
					}
				}
				?>
				<div style="<?php echo $style; ?>">
					<link href="index.php?controller=pjFront&action=pjActionLoadCss&cid=<?php echo @$_GET['cid']?>" type="text/css" rel="stylesheet" />
					<script type="text/javascript" src="index.php?controller=pjFront&action=pjActionLoad&cid=<?php echo @$_GET['cid']; ?><?php echo isset($_GET['view']) && (int) $_GET['view'] > 0 ? '&view=' . (int) $_GET['view'] : NULL; ?><?php echo isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? '&locale=' . (int) $_GET['locale'] : NULL; ?>"></script>
				</div>
				<?php
			} elseif ($_GET['cid'] == 'all') {
				?>
				<div style="max-width: 800px; height: 650px">
					<link href="index.php?controller=pjFront&action=pjActionLoadAvailabilityCss" type="text/css" rel="stylesheet" />
					<script type="text/javascript" src="index.php?controller=pjFront&action=pjActionLoadAvailability<?php echo isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? '&locale=' . (int) $_GET['locale'] : NULL; ?>"></script>
				</div>
				<?php
			}
		}
		?>
	</body>
</html>