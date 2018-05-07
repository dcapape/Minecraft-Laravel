<?php

class Convert
{
    public static function toLongTime($seconds)
    {
        $seconds = (int)$seconds;
        $dateTime = new DateTime();
        $dateTime->sub(new DateInterval("PT{$seconds}S"));
        $interval = (new DateTime())->diff($dateTime);
        $pieces = explode(' ', $interval->format('%y %m %d %h %i'));
        $intervals = ['year', 'month', 'day', 'hour', 'minute'];
        $result = [];
        foreach ($pieces as $i => $value) {
            if (!$value) {
                continue;
            }
            $periodName = $intervals[$i];
            if ($value > 1) {
                $periodName .= 's';
            }
            $result[] = "{$value} {$periodName}";
        }
        return implode(', ', $result);
    }

    public static function toHours($s){
      $h = 0; $m = 0;
      while($s >= 60){$s -= 60;$m++;}
      while($m >= 60){$m -=60;$h++;}
      $h = str_pad(intval($h), 2, '0', STR_PAD_LEFT);
      $m = str_pad(intval($m), 2, '0', STR_PAD_LEFT);
      $s = str_pad(intval($s), 2, '0', STR_PAD_LEFT);
      $units = [$h, $m];
      $intervals = ['hour', 'minute'];

      foreach ($units as $i => $value) {
          if (!$value) {
              continue;
          }
          $periodName = $intervals[$i];
          if ($value > 1) {
              $periodName .= 's';
          }
          $result[] = "{$value} {$periodName}";
      }
      return implode(', ', $result);
    }

    public static function toKms($cms){
      return number_format(round($cms / 100000, 2), 2);
    }


    public static function parseMCtoHTML($text){
      /*$string = utf8_decode(htmlspecialchars($string, ENT_QUOTES, "UTF-8"));
      $string = preg_replace('/\xA7([0-9a-f])/i', '<span class="mc-color mc-$1">', $string, -1, $count) . str_repeat("</span>", $count);
      return utf8_encode(preg_replace('/\xA7([k-or])/i', '<span class="mc-$1">', $string, -1, $count) . str_repeat("</span>", $count));*/
      $START_TAG  = '<span class="mc-color mc-%s">';
      $START_KTAG  = '<span class="monospace mc-color mc-%s">';
      $CLOSE_TAG  = '</span>';
      $EMPTY_TAGS = '/<[^\/>]*>([\s]?)*<\/[^>]*>/';
      $LINE_BREAK = '<br />';

      $colorscss = array(
    		'0' => '000000', //Black
    		'1' => '0000AA', //Dark Blue
    		'2' => '00AA00', //Dark Green
    		'3' => '00AAAA', //Dark Aqua
    		'4' => 'AA0000', //Dark Red
    		'5' => 'AA00AA', //Dark Purple
    		'6' => 'FFAA00', //Gold
    		'7' => 'AAAAAA', //Gray
    		'8' => '555555', //Dark Gray
    		'9' => '5555FF', //Blue
    		'a' => '55FF55', //Green
    		'b' => '55FFFF', //Aqua
    		'c' => 'FF5555', //Red
    		'd' => 'FF55FF', //Light Purple
    		'e' => 'FFFF55', //Yellow
    		'f' => 'FFFFFF'  //White
    	);

      if (mb_detect_encoding($text) != 'UTF-8')
			   $text = utf8_encode($text);

  		$text = htmlspecialchars($text);
  		preg_match_all('/(?:§|&amp;)([0-9a-fklmnor])/i', $text, $offsets);
  		$colors      = $offsets[0]; //This is what we are going to replace with HTML.
  		$color_codes = $offsets[1]; //This is the color numbers/characters only.
  		//No colors? Just return the text.
  		if (empty($colors))
  			return $text;
  		$open_tags = 0;
  		foreach ($colors as $index => $color) {
  			$color_code = strtolower($color_codes[$index]);
  			//We have a normal color.
  			if (isset($colorscss[$color_code])) {
  				$html = sprintf($START_TAG, $color_code);
  				//New color clears the other colors and formatting.
  				if ($open_tags != 0) {
  					$html = str_repeat($CLOSE_TAG, $open_tags).$html;
  					$open_tags = 0;
  				}
  				$open_tags++;
  			}else { //We have some formatting.
  				switch ($color_code) {
  					//Reset is special, just close all open tags.
  					case 'r':
  						$html = '';
  						if ($open_tags != 0) {
  							$html .= str_repeat($CLOSE_TAG, $open_tags);
  							$open_tags = 0;
  						}
              $html .= sprintf($START_TAG, $color_code);
  						break;
  					//Can't do obfuscated in CSS...
  					case 'k':
  						$html = sprintf($START_KTAG, $color_code);
              $open_tags++;
  						break;
  					default:
  						$html = sprintf($START_TAG, $color_code);
  						$open_tags++;
  						break;
  				}
  			}
  			//Replace the color with the HTML code. We use preg_replace because of the limit parameter.
  			$text = preg_replace('/'.$color.'/', $html, $text, 1);
  		}
  		//Still open tags? Close them!
  		if ($open_tags != 0)
  			$text = $text.str_repeat($CLOSE_TAG, $open_tags);
  		//Replace \n with <br />
  		/*if ($line_break_element) {
  			$text = str_replace("\n", $LINE_BREAK, $text);
  			$text = str_replace('\n', $LINE_BREAK, $text);
  		}*/
  		//Return the text without empty HTML tags. Only to clean up bad color formatting from the user.
  		return preg_replace($EMPTY_TAGS, '', $text);
    }


  /**
   * multi-purpose function to calculate the time elapsed between $start and optional $end
   * @param string|null $start the date string to start calculation
   * @param string|null $end the date string to end calculation
   * @param string $suffix the suffix string to include in the calculated string
   * @param string $format the format of the resulting date if limit is reached or no periods were found
   * @param string $separator the separator between periods to use when filter is not true
   * @param null|string $limit date string to stop calculations on and display the date if reached - ex: 1 month
   * @param bool|array $filter false to display all periods, true to display first period matching the minimum, or array of periods to display ['year', 'month']
   * @param int $minimum the minimum value needed to include a period
   * @return string
   */
  public static function elapsedTimeString($start, $end = null, $limit = null, $filter = true, $suffix = 'ago', $format = 'Y-m-d', $separator = ' ', $minimum = 1)
  {
      $dates = (object) array(
          'start' => new DateTime($start ? : 'now'),
          'end' => new DateTime($end ? : 'now'),
          'intervals' => array('y' => 'year', 'm' => 'month', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second'),
          'periods' => array()
      );
      $elapsed = (object) array(
          'interval' => $dates->start->diff($dates->end),
          'unknown' => 'unknown'
      );
      if ($elapsed->interval->invert === 1) {
          return trim('0 seconds ' . $suffix);
      }
      if (false === empty($limit)) {
          $dates->limit = new DateTime($limit);
          if (date_create()->add($elapsed->interval) > $dates->limit) {
              return $dates->start->format($format) ? : $elapsed->unknown;
          }
      }
      if (true === is_array($filter)) {
          $dates->intervals = array_intersect($dates->intervals, $filter);
          $filter = false;
      }
      foreach ($dates->intervals as $period => $name) {
          $value = $elapsed->interval->$period;
          if ($value >= $minimum) {
              $dates->periods[] = vsprintf('%1$s %2$s%3$s', array($value, $name, ($value !== 1 ? 's' : '')));
              if (true === $filter) {
                  break;
              }
          }
      }
      if (false === empty($dates->periods)) {
          return trim(vsprintf('%1$s %2$s', array(implode($separator, $dates->periods), $suffix)));
      }

      return $dates->start->format($format) ? : $elapsed->unknown;
  }


  public static function random($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
  {
      $pieces = [];
      $max = mb_strlen($keyspace, '8bit') - 1;
      for ($i = 0; $i < $length; ++$i) {
          $pieces []= $keyspace[random_int(0, $max)];
      }
      return implode('', $pieces);
  }

}
