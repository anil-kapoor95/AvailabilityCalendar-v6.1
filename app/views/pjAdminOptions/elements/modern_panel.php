<?php
/**
 * Modern: "Customize design" panel (Options > Appearance). Live preview + drawer with palettes, colors, font, corners, shadow.
 */
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
$acModernLabels = array(
	'opt_o_theme' => 'Design',
	'modern_option_classic' => 'Classic',
	'modern_customize' => 'Customize design',
	'modern_design_title' => 'Modern design',
	'modern_design_info' => 'Changes appear in the preview straight away. Press Save to publish them to your website.',
	'modern_need_theme' => 'Select "Modern" in the Design setting above and press Update, then you can customize its colors, font and corners here.',
	'modern_preview' => 'Live preview',
	'modern_palettes' => 'Colour palettes',
	'modern_surfaces' => 'Surfaces',
	'modern_text' => 'Text',
	'modern_buttons' => 'Buttons',
	'modern_availability' => 'Availability',
	'modern_c_page' => 'Page background',
	'modern_c_card' => 'Cards',
	'modern_c_soft' => 'Soft areas',
	'modern_c_line' => 'Borders',
	'modern_c_accent' => 'Accent',
	'modern_c_heading' => 'Headings',
	'modern_c_body' => 'Body text',
	'modern_c_muted' => 'Muted text',
	'modern_c_link' => 'Links',
	'modern_c_btn' => 'Primary button',
	'modern_c_btn_text' => 'Primary button text',
	'modern_c_btn_hover' => 'Primary button hover',
	'modern_c_btn2' => 'Round buttons',
	'modern_c_btn2_text' => 'Round buttons arrow',
	'modern_c_free' => 'Available',
	'modern_c_free_text' => 'Available text',
	'modern_c_booked' => 'Booked',
	'modern_c_booked_text' => 'Booked text',
	'modern_c_pending' => 'Pending',
	'modern_c_pending_text' => 'Pending text',
	'modern_c_past' => 'Past dates',
	'modern_c_past_text' => 'Past dates text',
	'modern_font' => 'Font',
	'modern_font_family' => 'Font family',
	'modern_font_size' => 'Text size',
	'modern_size_small' => 'Small',
	'modern_size_medium' => 'Medium',
	'modern_size_large' => 'Large',
	'modern_size_xlarge' => 'Extra large',
	'modern_corners' => 'Corners',
	'modern_sharp' => 'Sharp',
	'modern_soft' => 'Soft',
	'modern_round' => 'Round',
	'modern_shadow' => 'Shadow',
	'modern_shadow_none' => 'None',
	'modern_shadow_lifted' => 'Lifted',
	'modern_save' => 'Save',
	'modern_reset' => 'Reset to default',
	'modern_close' => 'Close',
	'modern_saved' => 'Design saved.',
	'modern_was_reset' => 'Design reset to the default.',
	'modern_error' => 'Could not save. Please try again.',
);
$acModern = array();
foreach ($acModernLabels as $k => $default)
{
	$v = __($k, true);
	$acModern[$k] = (is_string($v) && $v !== '' && $v !== $k) ? $v : $default;
}
$acModernActive = isset($tpl['option_arr']['o_theme']) && $tpl['option_arr']['o_theme'] == 'modern';
$acModernPalettes = array();
foreach (pjAppController::getModernPalettes() as $pk => $p)
{
	$acModernPalettes[$pk] = array('name' => $p[0], 'colors' => $p[1]);
}
$acModernFonts = array();
foreach (pjAppController::getModernFonts() as $fk => $f)
{
	$acModernFonts[$fk] = array('name' => $f[0], 'stack' => $f[1]);
}
$acModernConfig = array(
	'defaults' => pjAppController::getModernDefaults(),
	'saved' => (object) pjAppController::getModernSettings(isset($tpl['option_arr']['o_modern_colors']) ? $tpl['option_arr']['o_modern_colors'] : ''),
	'palettes' => $acModernPalettes,
	'fonts' => $acModernFonts,
	'radii' => pjAppController::getModernRadii(),
	'shadows' => pjAppController::getModernShadows(),
	't' => $acModern,
	'csrf' => pjAppController::getCsrfToken(),
	'cid' => (int) $controller->getForeignId(),
	'saveUrl' => $_SERVER['PHP_SELF'] . '?controller=pjAdminOptions&action=pjActionSaveModern',
	'previewUrl' => PJ_INSTALL_URL . 'preview.php?cid=' . (int) $controller->getForeignId()
);
?>
<style type="text/css">
#acModernBox{margin:18px 0 0;border:1px solid #e4e4e4;border-radius:6px;background:#fff;padding:14px 16px}
#acModernBox h3{margin:0 0 4px;font-size:15px}
#acModernBox p.acModernHint{margin:0 0 10px;color:#666;font-size:12.5px}
#acModernBox .acModernBar{display:flex;align-items:center;gap:10px;margin:0 0 10px}
#acModernOpen{border:0;border-radius:5px;background:#2a6fa8;color:#fff;font-size:13px;font-weight:600;padding:9px 16px;cursor:pointer}
#acModernOpen[disabled]{background:#9aa5af;cursor:not-allowed}
#acModernFrame{width:100%;height:520px;border:1px solid #e4e4e4;border-radius:6px;background:#f6f6f6}
#acModernPanel{position:fixed;top:0;right:0;bottom:0;width:340px;max-width:100%;z-index:100000;background:#fff;box-shadow:-10px 0 30px rgba(0,0,0,.2);display:none;flex-direction:column;font-size:13px}
#acModernPanel.open{display:flex}
#acModernPanel .h{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #e5e7eb}
#acModernPanel .h strong{font-size:15px}
#acModernPanel .x{border:0;background:none;font-size:24px;line-height:1;color:#666;cursor:pointer}
#acModernPanel .b{flex:1;overflow:auto;padding:4px 16px 16px}
#acModernPanel .i{margin:12px 0 4px;color:#666;font-size:12.5px}
#acModernPanel .s{padding:12px 0;border-bottom:1px solid #eef0f2}
#acModernPanel .s h4{margin:0 0 10px;font-size:11px;text-transform:uppercase;letter-spacing:.07em;color:#777}
#acModernPanel .pals{display:grid;grid-template-columns:1fr 1fr;gap:8px}
#acModernPanel .pal{border:1px solid #dfe3e7;background:#fff;border-radius:8px;padding:7px;text-align:left;font-size:12px;font-weight:600;color:#222;cursor:pointer;display:flex;flex-direction:column;gap:6px}
#acModernPanel .pal.on{border-color:#2a6fa8;box-shadow:0 0 0 2px rgba(42,111,168,.2)}
#acModernPanel .sw{display:flex;height:18px;border-radius:5px;overflow:hidden}
#acModernPanel .sw i{flex:1}
#acModernPanel .r{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:4px 0}
#acModernPanel .r input[type=color]{width:42px;height:28px;border:1px solid #ccd1d6;border-radius:5px;padding:2px;background:#fff;cursor:pointer}
#acModernPanel .r select{border:1px solid #ccd1d6;border-radius:5px;padding:5px 6px;max-width:180px}
#acModernPanel .seg{display:flex;gap:6px}
#acModernPanel .seg button{flex:1;border:1px solid #ccd1d6;background:#fff;border-radius:6px;padding:6px 4px;font-size:12px;font-weight:600;cursor:pointer}
#acModernPanel .seg button.on{background:#2a6fa8;border-color:#2a6fa8;color:#fff}
#acModernPanel .f{padding:12px 16px;border-top:1px solid #e5e7eb}
#acModernPanel .msg{min-height:16px;margin:0 0 8px;text-align:right;font-size:12px}
#acModernPanel .btns{display:flex;gap:10px}
#acModernPanel .btns button{flex:1;border-radius:6px;padding:10px;font-weight:600;font-size:13px;cursor:pointer;border:1px solid #ccd1d6;background:#fff}
#acModernPanel .btns .save{background:#2a6fa8;border-color:#2a6fa8;color:#fff}
</style>
<div id="acModernBox"<?php echo $acModernActive ? '' : ' style="display:none"'; ?>>
	<h3><?php echo htmlspecialchars($acModern['modern_design_title']); ?></h3>
	<?php
	if (!$acModernActive)
	{
		?><p class="acModernHint"><?php echo htmlspecialchars($acModern['modern_need_theme']); ?></p><?php
	} else {
		?><p class="acModernHint"><?php echo htmlspecialchars($acModern['modern_design_info']); ?></p><?php
	}
	?>
	<div class="acModernBar">
		<button type="button" id="acModernOpen"<?php echo $acModernActive ? '' : ' disabled="disabled"'; ?>><?php echo htmlspecialchars($acModern['modern_customize']); ?></button>
	</div>
	<?php
	if ($acModernActive)
	{
		?><iframe id="acModernFrame" title="<?php echo htmlspecialchars($acModern['modern_preview']); ?>" src="<?php echo htmlspecialchars($acModernConfig['previewUrl'], ENT_QUOTES); ?>"></iframe><?php
	}
	?>
</div>
<script type="text/javascript">
/* Modern selected: hide the Classic colour/font settings and show the customizer; Classic selected: the other way round. */
(function () {
	var sel = document.querySelector('select[name="value-enum-o_theme"]'), box = document.getElementById('acModernBox');
	if (!sel || !box) { return; }
	var row = sel.closest('tr'), rows = [].slice.call(row.parentNode.querySelectorAll('tr')).filter(function (r) { return r !== row; });
	function sync() {
		var modern = /::modern$/.test(sel.value);
		rows.forEach(function (r) { r.style.display = modern ? 'none' : ''; });
		box.style.display = modern ? '' : 'none';
	}
	sel.addEventListener('change', sync);
	sync();
})();
</script>
<?php
if ($acModernActive)
{
	?>
	<div id="acModernPanel" role="dialog" aria-label="<?php echo htmlspecialchars($acModern['modern_design_title']); ?>"></div>
	<script type="text/javascript">
	(function () {
		"use strict";
		var C = <?php echo json_encode($acModernConfig, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
			t = C.t,
			groups = [
				['modern_surfaces', ['page', 'card', 'soft', 'line', 'accent']],
				['modern_text', ['heading', 'body', 'muted', 'link']],
				['modern_buttons', ['btn', 'btn_text', 'btn_hover', 'btn2', 'btn2_text']],
				['modern_availability', ['free', 'free_text', 'booked', 'booked_text', 'pending', 'pending_text', 'past', 'past_text']]
			],
			sizes = [[13, 'modern_size_small'], [14, 'modern_size_medium'], [15, 'modern_size_large'], [16, 'modern_size_xlarge']],
			radii = [['sharp', 'modern_sharp'], ['soft', 'modern_soft'], ['round', 'modern_round']],
			shadows = [['none', 'modern_shadow_none'], ['soft', 'modern_soft'], ['lifted', 'modern_shadow_lifted']],
			$ = function (id) { return document.getElementById(id); },
			panel = $('acModernPanel'), frame = $('acModernFrame'), S;

		function clone(o) { return JSON.parse(JSON.stringify(o)); }
		function initState() {
			S = clone(C.defaults);
			var sv = C.saved || {};
			if (sv.palette !== undefined) { S.palette = sv.palette; }
			if (sv.colors) { for (var k in sv.colors) { S.colors[k] = sv.colors[k]; } }
			if (sv.font) { S.font = sv.font; }
			if (sv.fs) { S.fs = sv.fs; }
			if (sv.radius) { S.radius = sv.radius; }
			if (sv.shadow) { S.shadow = sv.shadow; }
		}
		function esc(s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;'); }
		function cssBlock() {
			var v = [], k, r = C.radii[S.radius];
			for (k in S.colors) { v.push('--modern-' + k.replace(/_/g, '-') + ':' + S.colors[k]); }
			v.push('--modern-font-head:' + C.fonts[S.font].stack, '--modern-font-body:' + C.fonts[S.font].stack, '--modern-fs:' + S.fs + 'px');
			v.push('--modern-r:' + r[0], '--modern-rb:' + r[1], '--modern-ri:' + r[2], '--modern-cell:' + r[3], '--modern-shadow:' + C.shadows[S.shadow]);
			return 'html #abWrapper_' + C.cid + '{' + v.join(';') + '}';
		}
		function applyLive() {
			try {
				var d = frame.contentDocument, st = d.getElementById('modern-live');
				if (!st) { st = d.createElement('style'); st.id = 'modern-live'; d.head.appendChild(st); }
				st.textContent = cssBlock();
			} catch (e) {}
		}
		function build() {
			var h = '<div class="h"><strong>' + esc(t.modern_design_title) + '</strong><button type="button" class="x" data-act="close" aria-label="' + esc(t.modern_close) + '">&times;</button></div><div class="b">';
			h += '<p class="i">' + esc(t.modern_design_info) + '</p>';
			h += '<div class="s"><h4>' + esc(t.modern_palettes) + '</h4><div class="pals">';
			for (var pk in C.palettes) {
				var c = C.palettes[pk].colors;
				h += '<button type="button" class="pal' + (S.palette === pk ? ' on' : '') + '" data-pal="' + pk + '"><span class="sw"><i style="background:' + c.page + '"></i><i style="background:' + c.soft + '"></i><i style="background:' + c.accent + '"></i><i style="background:' + c.btn + '"></i></span>' + esc(C.palettes[pk].name) + '</button>';
			}
			h += '</div></div>';
			groups.forEach(function (g) {
				h += '<div class="s"><h4>' + esc(t[g[0]]) + '</h4>';
				g[1].forEach(function (k) {
					h += '<div class="r"><label for="modernc_' + k + '">' + esc(t['modern_c_' + k]) + '</label><input type="color" id="modernc_' + k + '" data-key="' + k + '" value="' + S.colors[k] + '"></div>';
				});
				h += '</div>';
			});
			h += '<div class="s"><h4>' + esc(t.modern_font) + '</h4><div class="r"><label for="modernfont">' + esc(t.modern_font_family) + '</label><select id="modernfont">';
			for (var fk in C.fonts) { h += '<option value="' + fk + '"' + (S.font === fk ? ' selected' : '') + '>' + esc(C.fonts[fk].name) + '</option>'; }
			h += '</select></div><div class="r"><label for="modernfs">' + esc(t.modern_font_size) + '</label><select id="modernfs">';
			sizes.forEach(function (z) { h += '<option value="' + z[0] + '"' + (S.fs === z[0] ? ' selected' : '') + '>' + esc(t[z[1]]) + '</option>'; });
			h += '</select></div></div>';
			h += '<div class="s"><h4>' + esc(t.modern_corners) + '</h4><div class="seg">';
			radii.forEach(function (z) { h += '<button type="button" data-radius="' + z[0] + '" class="' + (S.radius === z[0] ? 'on' : '') + '">' + esc(t[z[1]]) + '</button>'; });
			h += '</div></div><div class="s"><h4>' + esc(t.modern_shadow) + '</h4><div class="seg">';
			shadows.forEach(function (z) { h += '<button type="button" data-shadow="' + z[0] + '" class="' + (S.shadow === z[0] ? 'on' : '') + '">' + esc(t[z[1]]) + '</button>'; });
			h += '</div></div></div><div class="f"><p class="msg" id="modernmsg" role="status"></p><div class="btns"><button type="button" data-act="reset">' + esc(t.modern_reset) + '</button><button type="button" class="save" data-act="save">' + esc(t.modern_save) + '</button></div></div>';
			panel.innerHTML = h;
		}
		function msg(text, ok) {
			var m = $('modernmsg');
			if (m) { m.textContent = text; m.style.color = ok ? '#15803d' : '#b91c1c'; }
		}
		function post(data, done) {
			var x = new XMLHttpRequest(), p = [];
			data.csrf_token = C.csrf;
			for (var k in data) { p.push(encodeURIComponent(k) + '=' + encodeURIComponent(data[k])); }
			x.open('POST', C.saveUrl, true);
			x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			x.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			x.onreadystatechange = function () {
				if (x.readyState !== 4) { return; }
				var r = null;
				try { r = JSON.parse(x.responseText); } catch (e) {}
				done(x.status === 200 && r && r.status === 'OK', r);
			};
			x.send(p.join('&'));
		}
		function open(o) { panel.classList.toggle('open', o); }

		$('acModernOpen').addEventListener('click', function () { open(true); });
		panel.addEventListener('click', function (e) {
			var b = e.target.closest('button');
			if (!b) { return; }
			if (b.dataset.pal) {
				var keep = {}, st = ['free', 'free_text', 'booked', 'booked_text', 'pending', 'pending_text', 'past', 'past_text'], k;
				st.forEach(function (n) { keep[n] = S.colors[n]; });
				S.palette = b.dataset.pal;
				S.colors = Object.assign({}, C.palettes[b.dataset.pal].colors, keep);
				build(); applyLive();
			} else if (b.dataset.radius) { S.radius = b.dataset.radius; build(); applyLive(); }
			else if (b.dataset.shadow) { S.shadow = b.dataset.shadow; build(); applyLive(); }
			else if (b.dataset.act === 'close') { open(false); }
			else if (b.dataset.act === 'reset') {
				post({reset: 1}, function (ok) {
					if (ok) { initStateDefaults(); build(); applyLive(); msg(t.modern_was_reset, true); } else { msg(t.modern_error, false); }
				});
			} else if (b.dataset.act === 'save') {
				post({settings: JSON.stringify(S)}, function (ok) { msg(ok ? t.modern_saved : t.modern_error, ok); });
			}
		});
		panel.addEventListener('input', function (e) {
			if (e.target.dataset.key) {
				S.colors[e.target.dataset.key] = e.target.value;
				S.palette = '';
				[].forEach.call(panel.querySelectorAll('.pal'), function (x) { x.classList.remove('on'); });
				applyLive();
			}
		});
		panel.addEventListener('change', function (e) {
			if (e.target.id === 'modernfont') { S.font = e.target.value; applyLive(); }
			if (e.target.id === 'modernfs') { S.fs = parseInt(e.target.value, 10); applyLive(); }
		});
		function initStateDefaults() { S = clone(C.defaults); }
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { open(false); } });
		frame.addEventListener('load', applyLive);
		initState(); build(); applyLive();
	})();
	</script>
	<?php
}
?>
