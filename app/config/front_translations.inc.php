<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
/**
 * Built-in translations of the calendar's front-end text (labels, month names, weekdays).
 * Used when a language is added (Options > Languages) and as a fallback while a label of a language
 * is still the untranslated English copy. Keys are the language code (the part of the locale ISO
 * before "-", e.g. ru-RU => ru). Anything the administrator edits under Options > Languages wins.
 *
 * Order: labels | months | short months | weekdays (Sunday first) | weekday initials (Sunday first)
 * Labels: back to calendars, prev month, next month, click note, loading calendars, loading calendar,
 * available, pending, booked, past.
 */
return array(
	'en' => array(
		array('Back to calendars', 'Prev month', 'Next month', 'Click on calendar name below to view calendar details.', 'Loading calendars ...', 'Loading calendar ...', 'Available', 'Pending', 'Booked', 'Past'),
		array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
		array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
		array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
		array('S', 'M', 'T', 'W', 'T', 'F', 'S'),
	),
	'ru' => array(
		array('Назад к календарям', 'Пред. месяц', 'След. месяц', 'Нажмите на название календаря ниже, чтобы увидеть подробности.', 'Загрузка календарей ...', 'Загрузка календаря ...', 'Свободно', 'В ожидании', 'Занято', 'Прошедшие'),
		array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'),
		array('янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'),
		array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'),
		array('В', 'П', 'В', 'С', 'Ч', 'П', 'С'),
	),
	'de' => array(
		array('Zurück zu den Kalendern', 'Vorheriger Monat', 'Nächster Monat', 'Klicken Sie unten auf einen Kalendernamen, um die Details anzuzeigen.', 'Kalender werden geladen ...', 'Kalender wird geladen ...', 'Verfügbar', 'Ausstehend', 'Gebucht', 'Vergangen'),
		array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'),
		array('Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'),
		array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'),
		array('S', 'M', 'D', 'M', 'D', 'F', 'S'),
	),
	'fr' => array(
		array('Retour aux calendriers', 'Mois précédent', 'Mois suivant', 'Cliquez sur le nom d\'un calendrier ci-dessous pour voir ses détails.', 'Chargement des calendriers ...', 'Chargement du calendrier ...', 'Disponible', 'En attente', 'Réservé', 'Passé'),
		array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
		array('janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'),
		array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'),
		array('D', 'L', 'M', 'M', 'J', 'V', 'S'),
	),
	'es' => array(
		array('Volver a los calendarios', 'Mes anterior', 'Mes siguiente', 'Haga clic en el nombre de un calendario para ver sus detalles.', 'Cargando calendarios ...', 'Cargando calendario ...', 'Disponible', 'Pendiente', 'Reservado', 'Pasado'),
		array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'),
		array('ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'),
		array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'),
		array('D', 'L', 'M', 'X', 'J', 'V', 'S'),
	),
	'it' => array(
		array('Torna ai calendari', 'Mese precedente', 'Mese successivo', 'Fai clic sul nome di un calendario qui sotto per vedere i dettagli.', 'Caricamento calendari ...', 'Caricamento calendario ...', 'Disponibile', 'In attesa', 'Prenotato', 'Passato'),
		array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'),
		array('gen', 'feb', 'mar', 'apr', 'mag', 'giu', 'lug', 'ago', 'set', 'ott', 'nov', 'dic'),
		array('Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'),
		array('D', 'L', 'M', 'M', 'G', 'V', 'S'),
	),
	'pt' => array(
		array('Voltar aos calendários', 'Mês anterior', 'Próximo mês', 'Clique no nome de um calendário abaixo para ver os detalhes.', 'A carregar calendários ...', 'A carregar calendário ...', 'Disponível', 'Pendente', 'Reservado', 'Passado'),
		array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'),
		array('jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'),
		array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'),
		array('D', 'S', 'T', 'Q', 'Q', 'S', 'S'),
	),
	'ro' => array(
		array('Înapoi la calendare', 'Luna anterioară', 'Luna următoare', 'Faceți clic pe numele unui calendar de mai jos pentru a vedea detaliile.', 'Se încarcă calendarele ...', 'Se încarcă calendarul ...', 'Disponibil', 'În așteptare', 'Rezervat', 'Trecut'),
		array('Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie', 'Iulie', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie'),
		array('ian', 'feb', 'mar', 'apr', 'mai', 'iun', 'iul', 'aug', 'sep', 'oct', 'noi', 'dec'),
		array('Duminică', 'Luni', 'Marți', 'Miercuri', 'Joi', 'Vineri', 'Sâmbătă'),
		array('D', 'L', 'M', 'M', 'J', 'V', 'S'),
	),
	'nl' => array(
		array('Terug naar kalenders', 'Vorige maand', 'Volgende maand', 'Klik hieronder op een kalendernaam om de details te bekijken.', 'Kalenders laden ...', 'Kalender laden ...', 'Beschikbaar', 'In behandeling', 'Geboekt', 'Verleden'),
		array('Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'),
		array('jan', 'feb', 'mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec'),
		array('Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'),
		array('Z', 'M', 'D', 'W', 'D', 'V', 'Z'),
	),
	'pl' => array(
		array('Wróć do kalendarzy', 'Poprzedni miesiąc', 'Następny miesiąc', 'Kliknij nazwę kalendarza poniżej, aby zobaczyć szczegóły.', 'Ładowanie kalendarzy ...', 'Ładowanie kalendarza ...', 'Dostępne', 'Oczekujące', 'Zarezerwowane', 'Minione'),
		array('Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'),
		array('sty', 'lut', 'mar', 'kwi', 'maj', 'cze', 'lip', 'sie', 'wrz', 'paź', 'lis', 'gru'),
		array('Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota'),
		array('N', 'P', 'W', 'Ś', 'C', 'P', 'S'),
	),
	'tr' => array(
		array('Takvimlere dön', 'Önceki ay', 'Sonraki ay', 'Ayrıntıları görmek için aşağıdaki bir takvim adına tıklayın.', 'Takvimler yükleniyor ...', 'Takvim yükleniyor ...', 'Müsait', 'Beklemede', 'Rezerve', 'Geçmiş'),
		array('Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'),
		array('Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'),
		array('Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'),
		array('P', 'P', 'S', 'Ç', 'P', 'C', 'C'),
	),
);
