<?php

namespace App;

class NeaWeatherForecastAbbrev
{
    public static function interpret($abbrev)
    {
        $interpret = "";
        switch($abbrev) {
            case "BR": $interpret = "Mist"; break;
            case "CL": $interpret = "Cloudy"; break;
            case "DR": $interpret = "Drizzle"; break;
            case "FA": $interpret = "Fair (Day)"; break;
            case "FG": $interpret = "Fog"; break;
            case "FN": $interpret = "Fair (Night)"; break;
            case "FW": $interpret = "Fair & Warm"; break;
            case "HG": $interpret = "Heavy Thundery Showers with Gusty Winds"; break;
            case "HR": $interpret = "Heavy Rain"; break;
            case "HS": $interpret = "Heavy Showers"; break;
            case "HT": $interpret = "Heavy Thundery Showers"; break;
            case "HZ": $interpret = "Hazy"; break;
            case "LH": $interpret = "Slightly Hazy"; break;
            case "LR": $interpret = "Light Rain"; break;
            case "LS": $interpret = "Light Showers"; break;
            case "OC": $interpret = "Overcast"; break;
            case "PC": $interpret = "Partly Cloudy (Day)"; break;
            case "PN": $interpret = "Partly Cloudy (Night)"; break;
            case "PS": $interpret = "Passing Showers"; break;
            case "RA": $interpret = "Moderate Rain"; break;
            case "SH": $interpret = "Showers"; break;
            case "SK": $interpret = "Strong Winds, Showers"; break;
            case "SN": $interpret = "Snow"; break;
            case "SR": $interpret = "Strong Winds, Rain"; break;
            case "SS": $interpret = "Snow Showers"; break;
            case "SU": $interpret = "Sunny"; break;
            case "SW": $interpret = "Strong Winds"; break;
            case "TL": $interpret = "Thundery Showers"; break;
            case "WC": $interpret = "Windy, Cloudy"; break;
            case "WD": $interpret = "Windy"; break;
            case "WF": $interpret = "Windy, Fair"; break;
            case "WR": $interpret = "Windy, Rain"; break;
            case "WS": $interpret = "Windy, Showers"; break;
        }
        return $interpret;
    }
}
