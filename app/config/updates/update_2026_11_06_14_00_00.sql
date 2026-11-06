START TRANSACTION;

-- upgrade scripts that ran an earlier version of this file (identifiers were renamed to "modern")
UPDATE `options` SET `key` = 'o_modern_colors' WHERE `key` = 'o_theme11_colors';
UPDATE `options` SET `value` = REPLACE(`value`, 'theme11', 'modern') WHERE `key` = 'o_theme';
UPDATE `fields` SET `key` = REPLACE(`key`, 'theme11_', 'modern_') WHERE `key` LIKE 'theme11\_%';

-- Modern option for every existing calendar (Modern is the default)
INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`)
SELECT c.`id`, 'o_theme', 2, 'classic|modern::modern', 'Classic|Modern', 'enum', 0, 1, '' FROM `calendars` c
WHERE NOT EXISTS (SELECT 1 FROM `options` o WHERE o.`foreign_id` = c.`id` AND o.`key` = 'o_theme');

INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`)
SELECT c.`id`, 'o_modern_colors', 2, '', '', 'string', 0, 0, '' FROM `calendars` c
WHERE NOT EXISTS (SELECT 1 FROM `options` o WHERE o.`foreign_id` = c.`id` AND o.`key` = 'o_modern_colors');

-- opt_o_theme
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'opt_o_theme', 'backend', 'Options / Design', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'opt_o_theme');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'opt_o_theme' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Design', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_option_classic
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_option_classic', 'backend', 'Modern / Classic', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_option_classic');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_option_classic' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Classic', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_customize
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_customize', 'backend', 'Modern / Customize design', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_customize');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_customize' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Customize design', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_design_title
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_design_title', 'backend', 'Modern / Panel title', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_design_title');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_design_title' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Modern design', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_design_info
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_design_info', 'backend', 'Modern / Panel hint', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_design_info');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_design_info' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Changes appear in the preview straight away. Press Save to publish them to your website.', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_need_theme
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_need_theme', 'backend', 'Modern / Choose Modern hint', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_need_theme');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_need_theme' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select "Modern" in the Design setting above and press Update, then you can customize its colors, font and corners here.', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_preview
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_preview', 'backend', 'Modern / Live preview', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_preview');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_preview' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Live preview', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_palettes
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_palettes', 'backend', 'Modern / Colour palettes', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_palettes');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_palettes' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Colour palettes', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_surfaces
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_surfaces', 'backend', 'Modern / Surfaces', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_surfaces');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_surfaces' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Surfaces', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_text
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_text', 'backend', 'Modern / Text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_text');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_text' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Text', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_buttons
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_buttons', 'backend', 'Modern / Buttons', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_buttons');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_buttons' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Buttons', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_availability
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_availability', 'backend', 'Modern / Availability', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_availability');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_availability' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Availability', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_page
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_page', 'backend', 'Modern / Page background', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_page');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_page' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Page background', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_card
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_card', 'backend', 'Modern / Cards', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_card');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_card' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cards', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_soft
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_soft', 'backend', 'Modern / Soft areas', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_soft');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_soft' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Soft areas', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_line
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_line', 'backend', 'Modern / Borders', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_line');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_line' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Borders', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_accent
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_accent', 'backend', 'Modern / Accent', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_accent');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_accent' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Accent', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_heading
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_heading', 'backend', 'Modern / Headings', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_heading');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_heading' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Headings', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_body
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_body', 'backend', 'Modern / Body text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_body');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_body' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Body text', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_muted
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_muted', 'backend', 'Modern / Muted text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_muted');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_muted' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Muted text', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_link
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_link', 'backend', 'Modern / Links', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_link');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_link' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Links', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_btn
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_btn', 'backend', 'Modern / Primary button', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_btn');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_btn' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Primary button', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_btn_text
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_btn_text', 'backend', 'Modern / Primary button text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_btn_text');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_btn_text' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Primary button text', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_btn_hover
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_btn_hover', 'backend', 'Modern / Primary button hover', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_btn_hover');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_btn_hover' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Primary button hover', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_btn2
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_btn2', 'backend', 'Modern / Round buttons', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_btn2');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_btn2' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Round buttons', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_btn2_text
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_btn2_text', 'backend', 'Modern / Round buttons arrow', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_btn2_text');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_btn2_text' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Round buttons arrow', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_free
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_free', 'backend', 'Modern / Available', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_free');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_free' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Available', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_free_text
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_free_text', 'backend', 'Modern / Available text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_free_text');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_free_text' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Available text', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_booked
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_booked', 'backend', 'Modern / Booked', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_booked');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_booked' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booked', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_booked_text
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_booked_text', 'backend', 'Modern / Booked text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_booked_text');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_booked_text' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booked text', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_pending
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_pending', 'backend', 'Modern / Pending', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_pending');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_pending' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Pending', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_pending_text
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_pending_text', 'backend', 'Modern / Pending text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_pending_text');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_pending_text' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Pending text', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_past
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_past', 'backend', 'Modern / Past dates', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_past');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_past' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Past dates', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_c_past_text
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_c_past_text', 'backend', 'Modern / Past dates text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_c_past_text');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_c_past_text' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Past dates text', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_font
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_font', 'backend', 'Modern / Font', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_font');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_font' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Font', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_font_family
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_font_family', 'backend', 'Modern / Font family', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_font_family');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_font_family' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Font family', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_font_size
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_font_size', 'backend', 'Modern / Text size', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_font_size');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_font_size' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Text size', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_size_small
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_size_small', 'backend', 'Modern / Small', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_size_small');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_size_small' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Small', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_size_medium
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_size_medium', 'backend', 'Modern / Medium', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_size_medium');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_size_medium' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Medium', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_size_large
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_size_large', 'backend', 'Modern / Large', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_size_large');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_size_large' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Large', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_size_xlarge
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_size_xlarge', 'backend', 'Modern / Extra large', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_size_xlarge');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_size_xlarge' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extra large', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_corners
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_corners', 'backend', 'Modern / Corners', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_corners');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_corners' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Corners', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_sharp
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_sharp', 'backend', 'Modern / Sharp', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_sharp');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_sharp' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Sharp', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_soft
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_soft', 'backend', 'Modern / Soft', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_soft');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_soft' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Soft', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_round
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_round', 'backend', 'Modern / Round', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_round');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_round' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Round', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_shadow
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_shadow', 'backend', 'Modern / Shadow', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_shadow');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_shadow' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Shadow', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_shadow_none
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_shadow_none', 'backend', 'Modern / No shadow', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_shadow_none');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_shadow_none' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'None', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_shadow_lifted
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_shadow_lifted', 'backend', 'Modern / Lifted shadow', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_shadow_lifted');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_shadow_lifted' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Lifted', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_save
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_save', 'backend', 'Modern / Save', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_save');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_save' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Save', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_reset
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_reset', 'backend', 'Modern / Reset to default', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_reset');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_reset' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Reset to default', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_close
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_close', 'backend', 'Modern / Close', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_close');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_close' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Close', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_saved
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_saved', 'backend', 'Modern / Saved message', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_saved');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_saved' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Design saved.', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_was_reset
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_was_reset', 'backend', 'Modern / Reset message', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_was_reset');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_was_reset' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Design reset to the default.', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- modern_error
INSERT INTO `fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'modern_error', 'backend', 'Modern / Error message', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `fields` WHERE `key` = 'modern_error');
SET @id := (SELECT `id` FROM `fields` WHERE `key` = 'modern_error' LIMIT 1);
INSERT INTO `multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjField', '::LOCALE::', 'title', 'Could not save. Please try again.', 'script' FROM DUAL
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `multi_lang` WHERE `foreign_id` = @id AND `model` = 'pjField' AND `locale` = '::LOCALE::' AND `field` = 'title');

-- rename for scripts that already ran an earlier version of this file
UPDATE `options` SET `label` = 'Classic|Modern' WHERE `key` = 'o_theme';
UPDATE `fields` SET `label` = REPLACE(`label`, 'Theme 11 /', 'Modern /') WHERE `key` LIKE 'modern\_%';
UPDATE `multi_lang` SET `content` = 'Modern design' WHERE `model` = 'pjField' AND `field` = 'title' AND `content` = 'Theme 11 design';
UPDATE `multi_lang` SET `content` = 'Select "Modern" in the Design setting above and press Update, then you can customize its colors, font and corners here.' WHERE `model` = 'pjField' AND `field` = 'title' AND `content` = 'Select "Theme 11" in the Design setting above and press Update, then you can customize its colors, font and corners here.';

-- make the admin and the front end reload the language labels
UPDATE `options` SET `value` = MD5(CONCAT(NOW(), RAND())) WHERE `key` = 'o_fields_index';

COMMIT;
