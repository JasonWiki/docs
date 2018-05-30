(function() {

/**
 * Y-m-d\\TH:i:sP
 * @constant
 * @return {String}
 */
Date.ATOM = 'Y-m-d\\TH:i:sP';
/**
 * l, d-M-y H:i:s T
 * @constant
 * @return {String}
 */
Date.COOKIE = 'l, d-M-y H:i:s T';
/**
 * Y-m-d\\TH:i:sP
 * @constant
 * @return {String}
 */
Date.ISO8601 = 'Y-m-d\\TH:i:sO';
/**
 * D, d M y H:i:s O
 * @constant
 * @return {String}
 */
Date.RFC822 = 'D, d M y H:i:s O';
/**
 * l, d-M-y H:i:s T
 * @constant
 * @return {String}
 */
Date.RFC850 = 'l, d-M-y H:i:s T';
/**
 * D, d M y H:i:s O
 * @constant
 * @return {String}
 */
Date.RFC1036 = 'D, d M y H:i:s O';
/**
 * D, d M Y H:i:s O
 * @constant
 * @return {String}
 */
Date.RFC1123 = 'D, d M Y H:i:s O';
/**
 * D, d M Y H:i:s O
 * @constant
 * @return {String}
 */
Date.RFC2822 = 'D, d M Y H:i:s O';
/**
 * Y-m-d\\TH:i:sP
 * @constant
 * @return {String}
 */
Date.RFC3339 = 'Y-m-d\\TH:i:sP';
/**
 * D, d M Y H:i:s O
 * @constant
 * @return {String}
 */
Date.RSS = 'D, d M Y H:i:s O';
/**
 * Y-m-d\\TH:i:sP
 * @constant
 * @return {String}
 */
Date.W3C = 'Y-m-d\\TH:i:sP';

/**
 * Month full names
 * @private
 */
var monthFullNames = [
	'January', 'February', 'March', 'April', 'May', 'June',
	'July', 'August', 'September', 'October', 'November', 'December'
];
/**
 * Month short names
 * @private
 */
var monthShortNames = [
	'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
	'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
];
/**
 * Day full names
 * @private
 */
var dayFullNames = [
	'Sunday', 'Monday', 'Tuesday', 'Wednesday',
	'Thursday', 'Friday', 'Saturday'
];
/**
 * Day short names
 * @private
 */
var dayShortNames = [
	'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
];
/**
 * Timezone data
 * @private
 */
var timezone = {
	id  : 'Asia/Tokyo',
	abbr: 'JST'
};
/**
 * Date.parse Original
 * @private
 */
var DateParse = Date.parse;
/**
 * Date.parse patterns
 * @private
 */
var parsePattern = [
	// [2009-08-01T01:02:03Z] or [2009-08-01T01:02:03+09:00]
	/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})([\+|\-]{1}\d{2}:\d{2})?/,
	// [Monday, 01-Aug-09 01:02:03 JST] or [Mon, 01 Aug 2009 01:02:03 +0900]
	/^[a-z]+, (\d{2,})[\s\-]([a-z]{3})[\s\-](\d{2}) (\d{2}):(\d{2}):(\d{2})\s*(.+)?/i
];
/**
 * is Opera
 * @private
 */
var isOpera = typeof window.opera !== 'undefined';
/**
 * inArray
 * @private
 */
function inArray(array, value) {
	if (array.indexOf) {
		return array.indexOf(value);
	}
	for (var i = 0, length = array.length; i < length; ++i) {
		if (array[i] === value) {
			return i;
		}
	}
	return -1;
}
/**
 * zero padding
 * @private
 */
function zp(value, digit) {
	digit = digit || 2;
	for (var i = 0; i < digit; ++i) {
		value = '0' + value;
	}
	return value.slice(-digit);
}

/**
 * Date.parse鍙娿伋瀹氭暟銇ф壉銇堛倠銉曘偐銉笺優銉冦儓銈掋儜銉笺偣銇椼仸UTC銉欍兗銈广伄UNIX銈裤偆銉犮偣銈裤兂銉椼倰杩斻仚
 *
 * @param  {String} dateString      瑕忔牸銇部銇ｃ仧鏃ヤ粯鏂囧瓧鍒� * @param  {Number} [baseYear=2000] 骞淬亴浜屾銇檪銉欍兗銈广伀銇倠骞� * @return {Number} UNIX銈裤偆銉犮偣銈裤兂銉� */
Date.parse = function(dateString, baseYear) {
	var m, time;

	// Opera銇疍ate.parse銇屽銇伄銇у彇銈婃暍銇堛仛NaN銇仚銈�	time = isOpera ? NaN : DateParse(dateString);

	if (!isNaN(time)) {
		return time;
	}

	// [2009-08-01T01:02:03Z] or [2009-08-01T01:02:03+09:00]
	if (m = dateString.match(parsePattern[0])) {
		time = Date.UTC(m[1], m[2] - 1, m[3], m[4], m[5], m[6]);
		return m[7] ? Date.applyDiffTime(time, m[7]) : time;
	}

	// [Monday, 01-Aug-09 01:02:03 JST] or [Mon, 01 Aug 2009 01:02:03 +0900]
	if (m = dateString.match(parsePattern[1])) {
		time = Date.UTC(+m[3] + (baseYear || 2000), inArray(monthShortNames, m[2]), m[1], m[4], m[5], m[6]);
		return m[7] ? Date.applyDiffTime(time, m[7]) : time;
	}

	// Opera銇嬨仱time銇孨aN銇倝Date.parse銈掗�銇�	return isOpera && isNaN(time) ? DateParse(dateString) : time;
};

/**
 * 鏅傚樊銈掗仼鐢ㄣ仚銈� *
 * @param  {Number} time 銉熴儶绉掑崢浣嶃伄UNIX銈裤偆銉犮偣銈裤兂銉� * @param  {String} diff 鏅傚樊鏂囧瓧鍒�(+0900 or +09:00 or JST)
 * @return {Number} 鏅傚樊銈掗仼鐢ㄣ仐銇烾NIX銈裤偆銉犮偣銈裤兂銉� */
Date.applyDiffTime = function(time, diff) {
	diff = diff.replace('JST', '+0900').replace('UTC', '+0000');
	var diffTime = diff.match(/\d{2}/g);
	diffTime = ((Math.abs(diffTime[0]) * 60) + Math.abs(diffTime[1])) * 60 * 1000;
	return diff.match(/^./)[0] === '-' ? time + diffTime : time - diffTime;
};

/**
 * ISO-8601 鏈堟洔鏃ャ伀濮嬨伨銈嬪勾鍗樹綅銇�鐣彿銈掕繑銇�br>
 * 鈥�鏈�鏃ュ強銇炽仢銇勾銇渶鍒濄伄鏈ㄦ洔鏃ャ亴鍚伨銈屻倠閫便亴闁嬪閫辩暘鍙� *
 * @param  {Number} [year=this.getFullYear()] 瀵捐薄骞� * @param  {Number} [month=this.getMonth()] 瀵捐薄鏈� * @param  {Number} [date=this.getDate()]  瀵捐薄鏃� * @return {Number} 閫辩暘鍙�(1-53)
 */
Date.prototype.getISOWeekNumber = function(year, month, date) {
	var _doy, doy;
	year  = year  || this.getFullYear();
	month = month || this.getMonth();
	date  = date  || this.getDate();
	// 浠婂勾銇�鏈�鏃ャ伄鏇滄棩鐣彿銇嬨倝1鏃�鏃�鏃ャ伄鍚堣▓3鏃ャ倰娓涚畻
	_doy = (new Date(year, 0, 4).getDay() || 7) - 3;
	// 涓婅銇х畻鍑恒仐銇熼�鐣彿闁嬪鏃ャ倰绲岄亷鏃ユ暟銇姞绠�	doy = _doy + this.getElapseDays(year);
	// 绲岄亷鏃ユ暟銇�浠ヤ笅銇牬鍚堛�鍓嶅勾銇嬨倝缍氥亸閫辩暘鍙�	if (doy <= 0) {
		year = year - 1;
		doy  = _doy + (new Date(year, 11, 31).getElapseDays(year));
	}
	// 12鏈�9鏃ャ倛銈婂墠銇亾銇撱仹绲備簡
	if (month < 11 || date < 29) {
		// 绠楀嚭銇曘倢銇熸棩鏁般倰涓��闁撳垎銇�銇у壊銇ｃ仸灏忔暟鐐逛互涓嬪垏銈婁笂銇�		return Math.ceil(doy / 7);
	}
	// 12鏈�9鏃ワ綖12鏈�1鏃ャ伄鏇滄棩銇屻仢銈屻仦銈屾湀銆佹湀or鐏�鏈堬綖姘淬仾銈夋鏈堛伄閫辩暘鍙烽枊濮�	if ((this.getDay() || 7) <= (3 - (31 - date))) {
		return 1;
	}
	// 绠楀嚭銇曘倢銇熸棩鏁般倰涓��闁撳垎銇�銇у壊銇ｃ仸灏忔暟鐐逛互涓嬪垏銈婁笂銇�	return Math.ceil(doy / 7);
};

/**
 * ISO-8601 閫辩暘鍙枫伀灞炪仚銈嬪勾銈掕繑銇� *
 * @param  {Number} [year=this.getFullYear()] 瀵捐薄骞� * @param  {Number} [month=this.getMonth()] 瀵捐薄鏈� * @param  {Number} [date=this.getDate()]  瀵捐薄鏃� * @return {Number} 骞� */
Date.prototype.getISOYear = function(year, month, date) {
	year  = year  || this.getFullYear();
	month = month || this.getMonth();
	date  = date  || this.getDate();
	weekNumber = this.getISOWeekNumber(year, month, date);
	return date <= 3 && weekNumber >= 52 ? year - 1
	     : date >= 29 && weekNumber == 1 ? year + 1
	     : year;
};

/**
 * getTime銇暟鍊ゃ亱銈夋檪宸倰寮曘亜銇熴儫銉鍗樹綅銇暟鍊ゃ倰杩斻仚
 *
 * @return {Number} 銉熴儶绉掑崢浣嶃伄鏁板�
 */
Date.prototype.getUTCTime = function() {
	return this.getTime() + (this.getTimezoneOffset() * 60 * 1000);
};

/**
 * 瀵捐薄骞淬亴銇嗐倠銇嗗勾銇嬨仼銇嗐亱
 *
 * @param  {Number}  [year=this.getFullYear()] 瀵捐薄骞� * @return {Boolean} 銇嗐倠銇嗗勾銇倝true銆佸钩骞淬仾銈塮alse
 */
Date.prototype.isLeapYear = function(year) {
	year = year || this.getFullYear();
	return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
};

/**
 * 鐝惧湪銇偆銉炽偪銉笺儘銉冦儓銈裤偆銉犮倰杩斻仚
 *
 * @param  {Number} [hour=GMT Hour] 瀵捐薄鏅� * @param  {Number} [min=GMT Min]   瀵捐薄鍒� * @param  {Number} [sec=GMT Sec]   瀵捐薄绉� * @return {String} 銈ゃ兂銈裤兗銉嶃儍銉堛偪銈ゃ儬
 */
Date.prototype.getInternetTime = function(hour, min, sec) {
	var beat, gmt = this.toGMTString().split(' ')[4].split(':');
	hour = hour || +gmt[0];
	min  = min  || +gmt[1];
	sec  = sec  || +gmt[2];
	beat = (hour * 3600 + min * 60 + sec + 3600) / 86.4;
	return zp(Math.floor(beat >= 1000 ? beat - 1000 : beat), 3);
};

/**
 * 瀵捐薄鏃ャ伄搴忔暟銈掕〃銇欍偟銉曘偅銉冦偗銈广倰杩斻仚
 *
 * @param  {Number} [date=this.getDate()] 瀵捐薄鏃� * @return {String} "st" or "nd" or "rd" or "th"
 */
Date.prototype.getSuffix = function(date) {
	date = '' + (date || this.getDate());
	var last = date.slice(-1);
	return last === '1' && date !== '11' ? 'st'
	     : last === '2' && date !== '12' ? 'nd'
	     : last === '3' && date !== '13' ? 'rd'
	     : 'th';
};

/**
 * 瀵捐薄骞存湀鏃ャ亱銈夈伄绲岄亷鏃ユ暟銈掕繑銇� *
 * @param  {Number} [year=this.getFullYear()] 瀵捐薄骞� * @param  {Number} [month=0] 瀵捐薄鏈� * @param  {Number} [date=1]  瀵捐薄鏃� * @return {Number} 绲岄亷鏃ユ暟
 */
Date.prototype.getElapseDays = function(year, month, date) {
	var start = new Date(year || this.getFullYear(), month || 0, date  || 1),
	    now   = new Date(this.getFullYear(), this.getMonth(), this.getDate());
	return Math.floor((now - start) / 60 / 60 / 24 / 1000);
};

/**
 * 瀵捐薄鏈堛伄鍏ㄦ棩鏁般倰杩斻仚
 *
 * @param  {Number} [year=this.getFullYear()] 瀵捐薄骞� * @param  {Number} [month=this.getMonth()]   瀵捐薄鏈� * @return {Number} 瀵捐薄鏈堛伄鍏ㄦ棩鏁� */
Date.prototype.getMonthTotalDays = function(year, month) {
	year  = year  || this.getFullYear();
	month = month || this.getMonth();
	return new Date(year, month + 1, 0).getDate();
};

/**
 * 12鏅傞枔鍗樹綅銇檪闁撱倰杩斻仚
 *
 * @param  {Number} [hour=this.getHours()] 瀵捐薄銇檪闁� * @return {Number} 12鏅傞枔鍗樹綅銇檪闁� */
Date.prototype.getHalfHours = function(hour) {
	hour = hour || this.getHours();
	return hour > 12 ? hour - 12 : hour === 0 ? 12 : hour;
};

/**
 * 銈般儶銉嬨儍銈告婧栨檪 (GMT) 銇ㄣ伄鏅傚樊銈掕繑銇� *
 * @param  {Boolean} [colon] true銇倝鏅傞枔銇ㄥ垎銈掋偝銉兂銇у尯鍒囥倠
 * @return {String}  鏅傚樊鏂囧瓧鍒�(+0900 or +09:00)
 */
Date.prototype.getGMTDiff = function(colon) {
	var offset = this.getTimezoneOffset() / 60;
	return (offset > 0 ? '-' : '+') + zp(Math.abs(offset)) + (colon ? ':' : '') + '00';
};

/**
 * PHP銇甦ate闁㈡暟銇ㄥ悓妲樸伄鏇稿紡銇ф棩浠樸倰銉曘偐銉笺優銉冦儓
 *
 * @example
 * var date = new Date();
 * date.format('Y-m-d H:i:s'); // 1970-01-01 00:00:00
 * @param  {String} format 銉曘偐銉笺優銉冦儓鏂囧瓧鍒� * @param  {Number|String} [timestamp] UNIX銈裤偆銉犮偣銈裤兂銉�銇俱仧銇�Date.parse銇с儜銉笺偣鍑烘潵銈嬫棩浠� * @return {String} 銉曘偐銉笺優銉冦儓鏂囧瓧鍒椼伀銇椼仧銇屻仯銇︺儠銈┿兗銉炪儍銉堛仌銈屻仧鏃ヤ粯
 * @see <a href="http://php.net/manual/ja/function.date.php">PHP: date - Manual</a>
 */
Date.prototype.format = function(format, timestamp) {
	if (!timestamp) {
		return _formatter.call(this, format);
	}
	if (typeof timestamp !== 'number') {
		timestamp = Date.parse(timestamp);
	}
	var _timestamp = this.getTime();
	this.setTime(timestamp);
	var ret = _formatter.call(this, format);
	this.setTime(_timestamp);
	return ret;
};

function _formatter(format) {
	// toString
	format = format + '';
	// Result
	var result = [];
	for (var i = 0, str; str = format.charAt(i); ++i) {
		if (str === '\\') {
			result[++i] = format.charAt(i);
			continue;
		}
		result[i]
			// [Day] 01 to 31
			= str === 'd' ? zp(this.getDate())
			// [Day] Mon through Sun
			: str === 'D' ? dayShortNames[this.getDay()]
			// [Day] 1 to 31
			: str === 'j' ? this.getDate()
			// [Day] Monday through Sunday
			: str === 'l' ? dayFullNames[this.getDay()]
			// [Day] 1 (for Monday) through 7 (for Sunday)
			: str === 'N' ? this.getDay() === 0 ? 7 : this.getDay()
			// [Day] st, nd, rd or th. Works well with j
			: str === 'S' ? this.getSuffix(this.getDate())
			// [Day] 0 (for Sunday) through 6 (for Saturday)
			: str === 'w' ? this.getDay()
			// [Day] 0 through 365
			: str === 'z' ? this.getElapseDays()

			// [Week] Example: 42 (the 42nd week in the year)
			: str === 'W' ? zp(this.getISOWeekNumber())

			// [Month] January through December
			: str === 'F' ? monthFullNames[this.getMonth()]
			// [Month] 01 through 12
			: str === 'm' ? zp(this.getMonth() + 1)
			// [Month] Jan through Dec
			: str === 'M' ? monthShortNames[this.getMonth()]
			// [Month] 1 through 12
			: str === 'n' ? this.getMonth() + 1
			// [Month] 28 through 31
			: str === 't' ? this.getMonthTotalDays()

			// [Year] 1 if it is a leap year, 0 otherwise.
			: str === 'L' ? this.isLeapYear() ? 1 : 0
			// [Year] Examples: 1999 or 2003 (ISO8601)
			: str === 'o' ? this.getISOYear()
			// [Year] Examples: 1999 or 2003
			: str === 'Y' ? this.getFullYear()
			// [Year] Examples: 99 or 03
			: str === 'y' ? (this.getFullYear() + '').slice(-2)

			// [Time] am or pm
			: str === 'a' ? this.getHours() < 12 ? 'am' : 'pm'
			// [Time] AM or PM
			: str === 'A' ? this.getHours() < 12 ? 'AM' : 'PM'
			// [Time] 000 through 999
			: str === 'B' ? this.getInternetTime()
			// [Time] 1 through 12
			: str === 'g' ? this.getHalfHours()
			// [Time] 0 through 23
			: str === 'G' ? this.getHours()
			// [Time] 01 through 12
			: str === 'h' ? zp(this.getHalfHours())
			// [Time] 00 through 23
			: str === 'H' ? zp(this.getHours())
			// [Time] 00 to 59
			: str === 'i' ? zp(this.getMinutes())
			// [Time] 00 through 59
			: str === 's' ? zp(this.getSeconds())
			// [Time] Example: 654321
			: str === 'u' ? zp(this.getMilliseconds(), 3) + '000'

			// [Timezone] Examples: UTC, GMT, Atlantic/Azores
			: str === 'e' ? timezone['id']
			// [Timezone] 1 if Daylight Saving Time, 0 otherwise.
			: str === 'I' ? 0
			// [Timezone] Example: +0900
			: str === 'O' ? this.getGMTDiff()
			// [Timezone] Example: +09:00
			: str === 'P' ? this.getGMTDiff(true)
			// [Timezone] Examples: EST, MDT ...
			: str === 'T' ? timezone['abbr']
			// [Timezone] -43200 through 50400
			: str === 'Z' ? (this.getTimezoneOffset() > 0 ? '-' : '')
			              + Math.abs(this.getTimezoneOffset() * 60)

			// [Full Date/Time] 2004-02-12T15:19:21+00:00
			// Date.ISO8601
			: str === 'c' ? arguments.callee.call(this, Date.ATOM)
			// [Full Date/Time] Example: Thu, 21 Dec 2000 16:01:07 +0200
			: str === 'r' ? arguments.callee.call(this, Date.RFC2822)
			// [Full Date/Time] Unix timestamp
			: str === 'U' ? (this.getTime() + '').slice(0, -3)

			// [NoMatch]
			: str;
	}
	return result.join('');
}

})();
