<?php
/**
 * 获取随机字符 此函数区分字符大小写 如果不区分大小写可加入函数strtolower
 * @param unknown $len
 * @return string
 */
function genRandomString($len)
{
    $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);// 将数组打乱
    $output = "";
    for ($i=0; $i<$len; $i++)
    {
        $output .= $chars[mt_rand(0, $charsLen)]; //获得一个数组元素
    }
    return $output;
}
/**
 * 检查确认密码和密码是否一致
 * @param unknown $password
 * @param unknown $repassword
 * @return boolean
 */
function checkPassword($password,$repassword){
    if(strcasecmp($password,$repassword)===0){
        return true;
    }
    return false;
}
/**
 * 通过IP获取地址
 * @param string $ip
 * @return string
 */
function ip2address($ip){

	$ipdatafile = C('IP_DATAS_PATH');
	static $fp = NULL, $offset = array(), $index = NULL;

	$ipdot = explode('.', $ip);
	$ip = pack('N', ip2long($ip));

	$ipdot[0] = (int)$ipdot[0];
	$ipdot[1] = (int)$ipdot[1];

	if($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
		$offset = @unpack('Nlen', @fread($fp, 4));
		$index = @fread($fp, $offset['len'] - 4);
	} elseif($fp == FALSE) {
		return false;
	}

	$length = $offset['len'] - 1028;
	$start = @unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

	for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {

		if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
			$index_offset = @unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
			$index_length = @unpack('Clen', $index{$start + 7});
			break;
		}
	}

	@fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
	if($index_length['len']) {
		return @fread($fp, $index_length['len']);
	} else {
		return 0;
	}
}

function URL($keyword, $params){

	$keyword = strtolower($keyword);
	$rules = C('URL_REVERSE_ROUTE_RULES');
	
	if ( array_key_exists($keyword, $rules) ){
		$urlFormat = $rules[$keyword] ;
		if (is_array($params)){
			$url = vsprintf($urlFormat, $params );
		}else{
			$url = sprintf($urlFormat, $params );
		}
	
	}
	
	if (!empty($url)){
		return U($url);
	}else{
		return U($keyword, $params);
	}
}

// 获取受Showtime限制的内容
function getCmsContent($modelid, $expirelevel){
      $modelid = intval($modelid);
      $expirelevel = intval($expirelevel);
      
       
       $redis = Cache::getInstance('Redis',C('LOCALREDIS'));
       $rediskey = 'CMS_' . $modelid;
       $keyExists = $redis->exists($rediskey);
        
        if ($keyExists){
            return $redis->get($rediskey);
        }
        
        $content = D('Cms_content');
        $map['Showtime'] = array('elt', date('Y-m-d H:i:s'));
        $map['Modelid'] = intval($modelid);
        $matchedcontent = $content->where($map)->order('Showtime desc')->find();
        //dump($matchedcontent);
        
        if ( isset($matchedcontent) && isset($matchedcontent['content']) ){
            $cmsmap = C('CMS_CONTENT_EXPIRE');
            $redis->set($rediskey, $matchedcontent['content'], $cmsmap[$expirelevel] );
            return $matchedcontent['content'];
        }

}

// 获取模块内容, 适合和Showtime无关的内容, 如banner, 公告等
function getBannerContent($modelid, $expirelevel){
      $modelid = intval($modelid);
      $expirelevel = intval($expirelevel);
      
       
       $redis = Cache::getInstance('Redis',C('LOCALREDIS'));
       $rediskey = 'CMS_' . $modelid;
       $keyExists = $redis->exists($rediskey);
        
        if ($keyExists){
            return $redis->get($rediskey);
        }
        
        $content = D('Cms_content');
        $map['Modelid'] = intval($modelid);
        $matchedcontent = $content->where($map)->find();
        //dump($matchedcontent);
        
        if ( isset($matchedcontent) && isset($matchedcontent['content']) ){
            $cmsmap = C('CMS_CONTENT_EXPIRE');
            $redis->set($rediskey, $matchedcontent['content'], $cmsmap[$expirelevel] );
            return $matchedcontent['content'];
        }

}

function  FormatComment($text){
    $text = trim($text);
    $pos = strpos("　", $text);
    if ( $pos == false || $pos != 0 ){
        $text = '　'.$text;
    }
    $text =str_replace(' ','```',$text);
    $text =preg_replace('/\s+/','</p><p>',$text);
    $text =str_replace('```', ' ',$text);   
    return "<p>".$text.'</p>';
}

function  FormatNovelContent($text){
    $text = trim($text);
    $pos = strpos("　　", $text);
    if ( $pos == false || $pos != 0 ){
        $text = '　　'.$text;
    }
    $text =str_replace(' ','```',$text);
    $text =preg_replace('/\s+/','</p><p>',$text);
    $text =str_replace('```', ' ',$text);   
    return "<p>".$text.'</p>';
}

function  FormatIntroContent($text, $lines=-1, $wordsperline=-1){
    //$text = strip_tags(trim($text));
    $text =str_replace(' ','```',$text);
    
    mb_regex_encoding('UTF-8');
    mb_internal_encoding("UTF-8"); 
    
    if ($lines == -1 && $wordsperline == -1){
        return "<p>".$text.'</p>';
    }
    $sentences = mb_split('\s+', $text);    
    
    $sentences = array_slice($sentences, 0, $lines);

   $oplines = 0;
    foreach ($sentences as $key => &$sentence ) {

        
        if ( $oplines < $lines){
               $oplines  += 1 + intval(mb_strlen($sentence, 'utf-8') / $wordsperline) ;
               continue;
        }
        
        if (  $oplines > $lines ){
            unset($sentences[$key]);
        }
            
    }
    $lastSentence = $sentences[count($sentences)-1];
   
    if ( $oplines>$lines ){
        $cutlen = ($oplines - $lines)*$wordsperline - 3;
        $cutlen = max( mb_strlen($lastSentence, 'utf-8') - $cutlen, 0);
        $lastSentence = cutString($lastSentence, $cutlen );
        $sentences[count($sentences)-1] = $lastSentence;   
    } 

    $text = implode("</p><p>", $sentences);  
    $text =str_replace('```', ' ',$text);   
    return "<p>".$text.'</p>';
}

function cutString($str, $len){
    
    $oldlen = mb_strlen($str, 'utf-8');
    if ( $oldlen <= $len){
        return $str;
    }
    $str = mb_substr($str, 0, $len, 'utf-8');
    return $str.'...';
}

function filterscript( $str ){  
          $str = strip_tags($str);
          $str = str_replace('<', '《', $str);
          $str = str_replace('>', '》', $str);            
          $str = str_replace( array('<','>',"'",'"',')','(',' ', '/', '\'','?'), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;', ' ', '', '', '？'),$str );
          $str = str_ireplace( '%3Cscript', '', $str);  
          return $str; 
}

function isutf8($string) {
    return preg_match('%^(?:
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);
}
//是否时间日期格式
function  isTimesStmp($subject){
    
    $pattern="/^[0-9]{4}(\-|\/)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(:[0-9]{1,2}){0,2})$/";
    return preg_match($pattern, $subject);
}

function cremove_xss($val) {
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
   // this prevents some character re-spacing such as <java\0script>
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

   // straight replacements, the user should never need these since they're normal characters
   // this prevents like <IMG SRC=@avascript:alert('XSS')>
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      // ;? matches the ;, which is optional
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

      // @ @ search for the hex values
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      // @ @ 0{0,7} matches '0' zero to seven times
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }

   // now the only remaining whitespace attacks are \t, \n, and \r
   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);

   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}
/**
 * isTelnum()
*
* @param mixed $value
* @return
*/
/**
 * isTelnum()
*
* @param mixed $value
* @return
*/
function isTelnum($value)
{
    return preg_match('/^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/', $value) === 1 ||
    preg_match('/^\d{3,4}-?\d{7,9}$/', $value) === 1;
}
/**
 * isEmail()
 *
 * @param mixed $value
 * @return
 */
function isEmail($value)
{
    return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $value) ===
    1;
}
/**
 * isQQ()
 *
 * @param mixed $value
 * @return
 */
function isQQ($value)
{
    return preg_match('/^[1-9]\d{4,9}$/', $value) === 1;
}
/**
 * isNum()
 *
 * @param mixed $value
 * @return
 */
function isNum($value)
{
    return preg_match('/^\d+$/', $value) === 1;
}
// 2~12 字符， 一个中文算2字符
function isNicknameValid($nick)
{
    $len = mixedStrlen($nick);
    if ($len > 12 || $len < 2)
    {
        return false;
    }
    return true;
}
function isCreditNo($vStr)
{
    if (!preg_match('/^([\d]{17}[xX\d])$/', $vStr))
        return false;
    return true;
}
/**
 * isPassword()
 *
 * @param mixed $value
 * @return
 */
function isPassword($value)
{
    return preg_match('/^[0-9a-z_!@#$%^&*()~+|]{6,16}$/', $value) == 1;
}

function isBooktitle($value)
{
    return preg_match('/^[\x{4e00}-\x{9fa5}A-Za-z0-9：，]{1,15}$/u', $value) == 1;
}
function isChinese($value)
{
    return preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $value) == 1;
}
function isChaptertitle($value)
{
    return preg_match('/^[a-zA-Z0-9\x{4e00}-\x{9fa5}：， ！。“”《》.？、]{1,15}$/u', $value) == 1;
}

function number2Chinese($num, $m = 1)
{
    switch ($m)
    {
        case 0:
            $CNum = array(
            array(
            '零',
            '壹',
            '贰',
            '叁',
            '肆',
            '伍',
            '陆',
            '柒',
            '捌',
            '玖'),
            array(
            '',
            '拾',
            '佰',
            '仟'),
            array(
            '',
            '萬',
            '億',
            '萬億'));
            break;
        default:
            $CNum = array(
            array(
            '零',
            '一',
            '二',
            '三',
            '四',
            '五',
            '六',
            '七',
            '八',
            '九'),
            array(
            '',
            '十',
            '百',
            '千'),
            array(
            '',
            '万',
            '亿',
            '万亿'));
            break;
    }
    // $cNum = array('零','一','二','三','四','五','六','七','八','九');

    if (is_integer($num))
    {
        $int = (string )$num;
    } else
    if (is_numeric($num))
    {
        $num = explode('.', (string )floatval($num));
        $int = $num[0];
        $fl = isset($num[1]) ? $num[1] : false;
    }
    // 长度
    $len = strlen($int);
    //dump($int);
    //dump($len);
    // 中文
    $chinese = array();

    // 反转的数字
    $str = strrev($int);
    for ($i = 0; $i < $len; $i += 4)
    {
        $s = array(
                0 => $str[$i],
                1 => $str[$i + 1],
                2 => $str[$i + 2],
                3 => $str[$i + 3]);
        //dump($s);
        $j = '';
        // 千位
        if ($s[3] !== '')
        {
            $s[3] = (int)$s[3];
            if ($s[3] !== 0)
            {
                $j .= $CNum[0][$s[3]] . $CNum[1][3];
            } else
            {
                if ($s[2] != 0 || $s[1] != 0 || $s[0] != 0)
                {
                    $j .= $CNum[0][0];
                }
            }
        }
        // 百位
        if ($s[2] !== '')
        {
            $s[2] = (int)$s[2];
            if ($s[2] !== 0)
            {
                $j .= $CNum[0][$s[2]] . $CNum[1][2];
            } else
            {
                if ($s[3] != 0 && ($s[1] != 0 || $s[0] != 0))
                {
                    $j .= $CNum[0][0];
                }
            }
        }
        // 十位
        if ($s[1] !== '')
        {
            $s[1] = (int)$s[1];
            if ($s[1] !== 0)
            {
                if ($len == 2 && $s[1] ==1)
                    $j .= $CNum[1][1];
                else
                    $j .= $CNum[0][$s[1]] . $CNum[1][1];
            } else
            {
                if ($s[0] != 0 && $s[2] != 0)
                {
                    $j .= $CNum[0][$s[1]];
                }
            }
        }
        // 个位
        if ($s[0] !== '')
        {
            $s[0] = (int)$s[0];
            if ($s[0] !== 0)
            {
                $j .= $CNum[0][$s[0]] . $CNum[1][0];
            } else
            {
                // $j .= $CNum[0][0];
            }
        }
        $j .= $CNum[2][$i / 4];
        array_unshift($chinese, $j);
    }
    $chs = implode('', $chinese);
    if ($fl)
    {
        $chs .= '点';
        for ($i = 0, $j = strlen($fl); $i < $j; $i++)
        {
            $t = (int)$fl[$i];
            $chs .= $str[0][$t];
        }
    }
    return $chs;
}
function ReplaceSpecialChar($C_char)
{
    $C_char = HTMLSpecialChars($C_char); //将特殊字元转成 HTML 格式。
    $C_char = preg_replace("/[\r\n]+/", '', $C_char); //将回车替换为
    $C_char = str_replace(" ", "", $C_char); //替换空格为
    $C_char = str_replace("　", "", $C_char); //替换空格为
    $C_char = str_replace("\t" , "" , $C_char);
    return $C_char;
}
function ReplaceSpaceChar($C_char)
{
    $C_char = HTMLSpecialChars($C_char); //将特殊字元转成 HTML 格式。
    $C_char = preg_replace("/[\r\n]+/", '', $C_char); //将回车替换为
    $C_char = str_replace("　", "", $C_char); //替换空格为
    return $C_char;
}
function ReplaceSpecialCharContent($C_char)
{
    $C_char = HTMLSpecialChars($C_char); //将特殊字元转成 HTML 格式。
    $C_char = str_replace(" ", "", $C_char); //替换空格为
    $C_char = str_replace("　", "", $C_char); //替换空格为
    $C_char = str_replace("\t" , "" , $C_char);
    $C_char = str_replace("," , "，" , $C_char);
    $C_char = str_replace("." , "。" , $C_char);
    $C_char = str_replace("&amp;quot;" , "“" , $C_char);
    $C_char = preg_replace("/([\r\n]+){2}/", '${1}', $C_char);
    $C_char = preg_replace("/([\r\n]+)/", '${1}　　', $C_char); //将回车替换为
    $C_char = str_replace("。。。" , "." , $C_char);
    return '    '.$C_char;
}
function GetWords($C_char)
{
    return mb_strlen(ReplaceSpecialChar($C_char), 'UTF-8');

}
function ccstrlen($str) #计算中英文混合字符串的长度
{
    $cclen = 0;
    $asclen = strlen($str);
    $ind = 0;
    $hascc = ereg("[xa1-xfe]", $str); #判断是否有汉字
    $hasasc = ereg("[x01-xa0]", $str); #判断是否有ascii字符
    if ($hascc && !$hasasc) #只有汉字的情况

        return strlen($str) / 3;
    if (!$hascc && $hasasc) #只有ascii字符的情况

        return strlen($str);
    for ($ind = 0; $ind < $asclen; $ind++)
    {
        if (ord(substr($str, $ind, 1)) > 0xa0)
        {
            $cclen++;
            $ind++;
        } else
        {
            $cclen++;
        }
    }
    return $cclen;
}
function ccstrleft($str, $len) #从左边截取中英文混合字符串
{
    $asclen = strlen($str);
    if ($asclen <= $len)
        return $str;
    $hascc = ereg("[xa1-xfe]", $str); #同上
    $hasasc = ereg("[x01-xa0]", $str);
    if (!$hascc)
        return substr($str, 0, $len);
    if (!$hasasc)
    if ($len & 0x01) #如果长度是奇数

        return substr($str, 0, $len + $len - 2);
    else
        return substr($str, 0, $len + $len);
    $cind = 0;
    $flag = 0;
    while ($cind < $asclen)
    {
        if (ord(substr($str, $cind, 1)) < 0xa1)
            $flag++;
        $cind++;
    }
    if ($flag & 0x01)
        return substr($str, 0, $len);
    else
        return substr($str, 0, $len - 1);
}
function Pinyin($_String, $_Code = 'gb2312')
{
    $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" .
            "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" .
            "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" .
            "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" .
            "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" .
            "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" .
            "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" .
            "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" .
            "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" .
            "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" .
            "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" .
            "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" .
            "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" .
            "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" .
            "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" .
            "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
    $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" .
            "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" .
            "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" .
            "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" .
            "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" .
            "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" .
            "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" .
            "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" .
            "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" .
            "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" .
            "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" .
            "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" .
            "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" .
            "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" .
            "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" .
            "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" .
            "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" .
            "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" .
            "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" .
            "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" .
            "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" .
            "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" .
            "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" .
            "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" .
            "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" .
            "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" .
            "|-10270|-10262|-10260|-10256|-10254";
    $_TDataKey = explode('|', $_DataKey);
    $_TDataValue = explode('|', $_DataValue);
    $_Data = (PHP_VERSION >= '5.0') ? array_combine($_TDataKey, $_TDataValue) :
    _Array_Combine($_TDataKey, $_TDataValue);
    arsort($_Data);
    reset($_Data);
    if ($_Code != 'gb2312')
        $_String = _U2_Utf8_Gb($_String);
    $_Res = '';
    for ($i = 0; $i < strlen($_String); $i++)
    {
        $_P = ord(substr($_String, $i, 1));
        if ($_P > 160)
        {
            $_Q = ord(substr($_String, ++$i, 1));
            $_P = $_P * 256 + $_Q - 65536;
        }
        $_Res .= _Pinyin($_P, $_Data);
    }
    return preg_replace("/[^a-z0-9]*/", '', $_Res);
}
function _Pinyin($_Num, $_Data)
{
    if ($_Num > 0 && $_Num < 160)
        return chr($_Num);
    elseif ($_Num < -20319 || $_Num > -10247)
    return '';
    else
    {
        foreach ($_Data as $k => $v)
        {
            if ($v <= $_Num)
                break;
        }
        return $k;
    }
}
function _U2_Utf8_Gb($_C)
{
    $_String = '';
    if ($_C < 0x80)
        $_String .= $_C;
    elseif ($_C < 0x800)
    {
        $_String .= chr(0xC0 | $_C >> 6);
        $_String .= chr(0x80 | $_C & 0x3F);
    } elseif ($_C < 0x10000)
    {
        $_String .= chr(0xE0 | $_C >> 12);
        $_String .= chr(0x80 | $_C >> 6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    } elseif ($_C < 0x200000)
    {
        $_String .= chr(0xF0 | $_C >> 18);
        $_String .= chr(0x80 | $_C >> 12 & 0x3F);
        $_String .= chr(0x80 | $_C >> 6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    }
    return iconv('UTF-8', 'GB2312', $_String);
}
function _Array_Combine($_Arr1, $_Arr2)
{
    for ($i = 0; $i < count($_Arr1); $i++)
        $_Res[$_Arr1[$i]] = $_Arr2[$i];
    return $_Res;
}
// 计算字符长度， 中文算2字节， 其他算一个字节, 已经用于昵称长度判断.
function mixedStrlen($str){
    return (strlen($str) + mb_strlen($str, 'UTF8')) / 2;
}
// 计算字符长度， 都算1字节
function utf8_strlen($string = null) {
    preg_match_all("/./us", $string, $match);
    return count($match[0]);
}

function addThumbPrefix($img_url)
{
    $tmp = strrpos($img_url, '/');

    $new_url = '';
    if ($tmp)
    {
        $new_url = substr($img_url, 0, $tmp+1).'m_'.substr($img_url, $tmp+1,strlen($img_url)-$tmp-1);
    }
    return $new_url;
}

function getImgUrl($img_url, $is_thumb=true)
{
    if ($img_url == '')
    {
        return WEBSITE . 'Public/images/empty_avatar.png';
    }
    else if ($is_thumb)
    {
        return WEBSITE . addThumbPrefix($img_url);
    }
    else
    {
        return WEBSITE . $img_url;
    }
}
