<?php
namespace lib;

class Charset
{

    public static $charset = array(
        'big5_chinese_ci' => array(
            'charset' => 'big5',
            'default' => true
        ),
        'big5_bin' => array(
            'charset' => 'big5',
            'default' => false
        ),
        'dec8_swedish_ci' => array(
            'charset' => 'dec8',
            'default' => true
        ),
        'dec8_bin' => array(
            'charset' => 'dec8',
            'default' => false
        ),
        'cp850_general_ci' => array(
            'charset' => 'cp850',
            'default' => true
        ),
        'cp850_bin' => array(
            'charset' => 'cp850',
            'default' => false
        ),
        'hp8_english_ci' => array(
            'charset' => 'hp8',
            'default' => true
        ),
        'hp8_bin' => array(
            'charset' => 'hp8',
            'default' => false
        ),
        'koi8r_general_ci' => array(
            'charset' => 'koi8r',
            'default' => true
        ),
        'koi8r_bin' => array(
            'charset' => 'koi8r',
            'default' => false
        ),
        'latin1_german1_ci' => array(
            'charset' => 'latin1',
            'default' => false
        ),
        'latin1_swedish_ci' => array(
            'charset' => 'latin1',
            'default' => true
        ),
        'latin1_danish_ci' => array(
            'charset' => 'latin1',
            'default' => false
        ),
        'latin1_german2_ci' => array(
            'charset' => 'latin1',
            'default' => false
        ),
        'latin1_bin' => array(
            'charset' => 'latin1',
            'default' => false
        ),
        'latin1_general_ci' => array(
            'charset' => 'latin1',
            'default' => false
        ),
        'latin1_general_cs' => array(
            'charset' => 'latin1',
            'default' => false
        ),
        'latin1_spanish_ci' => array(
            'charset' => 'latin1',
            'default' => false
        ),
        'latin2_czech_cs' => array(
            'charset' => 'latin2',
            'default' => false
        ),
        'latin2_general_ci' => array(
            'charset' => 'latin2',
            'default' => true
        ),
        'latin2_hungarian_ci' => array(
            'charset' => 'latin2',
            'default' => false
        ),
        'latin2_croatian_ci' => array(
            'charset' => 'latin2',
            'default' => false
        ),
        'latin2_bin' => array(
            'charset' => 'latin2',
            'default' => false
        ),
        'swe7_swedish_ci' => array(
            'charset' => 'swe7',
            'default' => true
        ),
        'swe7_bin' => array(
            'charset' => 'swe7',
            'default' => false
        ),
        'ascii_general_ci' => array(
            'charset' => 'ascii',
            'default' => true
        ),
        'ascii_bin' => array(
            'charset' => 'ascii',
            'default' => false
        ),
        'ujis_japanese_ci' => array(
            'charset' => 'ujis',
            'default' => true
        ),
        'ujis_bin' => array(
            'charset' => 'ujis',
            'default' => false
        ),
        'sjis_japanese_ci' => array(
            'charset' => 'sjis',
            'default' => true
        ),
        'sjis_bin' => array(
            'charset' => 'sjis',
            'default' => false
        ),
        'hebrew_general_ci' => array(
            'charset' => 'hebrew',
            'default' => true
        ),
        'hebrew_bin' => array(
            'charset' => 'hebrew',
            'default' => false
        ),
        'tis620_thai_ci' => array(
            'charset' => 'tis620',
            'default' => true
        ),
        'tis620_bin' => array(
            'charset' => 'tis620',
            'default' => false
        ),
        'euckr_korean_ci' => array(
            'charset' => 'euckr',
            'default' => true
        ),
        'euckr_bin' => array(
            'charset' => 'euckr',
            'default' => false
        ),
        'koi8u_general_ci' => array(
            'charset' => 'koi8u',
            'default' => true
        ),
        'koi8u_bin' => array(
            'charset' => 'koi8u',
            'default' => false
        ),
        'gb2312_chinese_ci' => array(
            'charset' => 'gb2312',
            'default' => true
        ),
        'gb2312_bin' => array(
            'charset' => 'gb2312',
            'default' => false
        ),
        'greek_general_ci' => array(
            'charset' => 'greek',
            'default' => true
        ),
        'greek_bin' => array(
            'charset' => 'greek',
            'default' => false
        ),
        'cp1250_general_ci' => array(
            'charset' => 'cp1250',
            'default' => true
        ),
        'cp1250_czech_cs' => array(
            'charset' => 'cp1250',
            'default' => false
        ),
        'cp1250_croatian_ci' => array(
            'charset' => 'cp1250',
            'default' => false
        ),
        'cp1250_bin' => array(
            'charset' => 'cp1250',
            'default' => false
        ),
        'cp1250_polish_ci' => array(
            'charset' => 'cp1250',
            'default' => false
        ),
        'gbk_chinese_ci' => array(
            'charset' => 'gbk',
            'default' => true
        ),
        'gbk_bin' => array(
            'charset' => 'gbk',
            'default' => false
        ),
        'latin5_turkish_ci' => array(
            'charset' => 'latin5',
            'default' => true
        ),
        'latin5_bin' => array(
            'charset' => 'latin5',
            'default' => false
        ),
        'armscii8_general_ci' => array(
            'charset' => 'armscii8',
            'default' => true
        ),
        'armscii8_bin' => array(
            'charset' => 'armscii8',
            'default' => false
        ),
        'utf8_general_ci' => array(
            'charset' => 'utf8',
            'default' => true
        ),
        'utf8_bin' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_unicode_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_icelandic_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_latvian_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_romanian_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_slovenian_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_polish_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_estonian_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_spanish_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_swedish_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_turkish_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_czech_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_danish_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_lithuanian_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_slovak_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_spanish2_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_roman_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_persian_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_esperanto_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_hungarian_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_sinhala_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_german2_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_croatian_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_unicode_520_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_vietnamese_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'utf8_general_mysql500_ci' => array(
            'charset' => 'utf8',
            'default' => false
        ),
        'ucs2_general_ci' => array(
            'charset' => 'ucs2',
            'default' => true
        ),
        'ucs2_bin' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_unicode_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_icelandic_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_latvian_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_romanian_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_slovenian_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_polish_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_estonian_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_spanish_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_swedish_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_turkish_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_czech_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_danish_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_lithuanian_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_slovak_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_spanish2_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_roman_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_persian_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_esperanto_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_hungarian_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_sinhala_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_german2_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_croatian_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_unicode_520_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_vietnamese_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'ucs2_general_mysql500_ci' => array(
            'charset' => 'ucs2',
            'default' => false
        ),
        'cp866_general_ci' => array(
            'charset' => 'cp866',
            'default' => true
        ),
        'cp866_bin' => array(
            'charset' => 'cp866',
            'default' => false
        ),
        'keybcs2_general_ci' => array(
            'charset' => 'keybcs2',
            'default' => true
        ),
        'keybcs2_bin' => array(
            'charset' => 'keybcs2',
            'default' => false
        ),
        'macce_general_ci' => array(
            'charset' => 'macce',
            'default' => true
        ),
        'macce_bin' => array(
            'charset' => 'macce',
            'default' => false
        ),
        'macroman_general_ci' => array(
            'charset' => 'macroman',
            'default' => true
        ),
        'macroman_bin' => array(
            'charset' => 'macroman',
            'default' => false
        ),
        'cp852_general_ci' => array(
            'charset' => 'cp852',
            'default' => true
        ),
        'cp852_bin' => array(
            'charset' => 'cp852',
            'default' => false
        ),
        'latin7_estonian_cs' => array(
            'charset' => 'latin7',
            'default' => false
        ),
        'latin7_general_ci' => array(
            'charset' => 'latin7',
            'default' => true
        ),
        'latin7_general_cs' => array(
            'charset' => 'latin7',
            'default' => false
        ),
        'latin7_bin' => array(
            'charset' => 'latin7',
            'default' => false
        ),
        'utf8mb4_general_ci' => array(
            'charset' => 'utf8mb4',
            'default' => true
        ),
        'utf8mb4_bin' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_unicode_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_icelandic_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_latvian_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_romanian_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_slovenian_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_polish_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_estonian_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_spanish_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_swedish_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_turkish_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_czech_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_danish_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_lithuanian_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_slovak_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_spanish2_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_roman_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_persian_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_esperanto_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_hungarian_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_sinhala_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_german2_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_croatian_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_unicode_520_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'utf8mb4_vietnamese_ci' => array(
            'charset' => 'utf8mb4',
            'default' => false
        ),
        'cp1251_bulgarian_ci' => array(
            'charset' => 'cp1251',
            'default' => false
        ),
        'cp1251_ukrainian_ci' => array(
            'charset' => 'cp1251',
            'default' => false
        ),
        'cp1251_bin' => array(
            'charset' => 'cp1251',
            'default' => false
        ),
        'cp1251_general_ci' => array(
            'charset' => 'cp1251',
            'default' => true
        ),
        'cp1251_general_cs' => array(
            'charset' => 'cp1251',
            'default' => false
        ),
        'utf16_general_ci' => array(
            'charset' => 'utf16',
            'default' => true
        ),
        'utf16_bin' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_unicode_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_icelandic_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_latvian_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_romanian_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_slovenian_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_polish_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_estonian_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_spanish_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_swedish_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_turkish_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_czech_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_danish_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_lithuanian_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_slovak_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_spanish2_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_roman_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_persian_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_esperanto_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_hungarian_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_sinhala_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_german2_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_croatian_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_unicode_520_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16_vietnamese_ci' => array(
            'charset' => 'utf16',
            'default' => false
        ),
        'utf16le_general_ci' => array(
            'charset' => 'utf16le',
            'default' => true
        ),
        'utf16le_bin' => array(
            'charset' => 'utf16le',
            'default' => false
        ),
        'cp1256_general_ci' => array(
            'charset' => 'cp1256',
            'default' => true
        ),
        'cp1256_bin' => array(
            'charset' => 'cp1256',
            'default' => false
        ),
        'cp1257_lithuanian_ci' => array(
            'charset' => 'cp1257',
            'default' => false
        ),
        'cp1257_bin' => array(
            'charset' => 'cp1257',
            'default' => false
        ),
        'cp1257_general_ci' => array(
            'charset' => 'cp1257',
            'default' => true
        ),
        'utf32_general_ci' => array(
            'charset' => 'utf32',
            'default' => true
        ),
        'utf32_bin' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_unicode_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_icelandic_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_latvian_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_romanian_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_slovenian_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_polish_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_estonian_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_spanish_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_swedish_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_turkish_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_czech_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_danish_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_lithuanian_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_slovak_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_spanish2_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_roman_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_persian_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_esperanto_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_hungarian_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_sinhala_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_german2_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_croatian_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_unicode_520_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'utf32_vietnamese_ci' => array(
            'charset' => 'utf32',
            'default' => false
        ),
        'binary' => array(
            'charset' => 'binary',
            'default' => true
        ),
        'geostd8_general_ci' => array(
            'charset' => 'geostd8',
            'default' => true
        ),
        'geostd8_bin' => array(
            'charset' => 'geostd8',
            'default' => false
        ),
        'cp932_japanese_ci' => array(
            'charset' => 'cp932',
            'default' => true
        ),
        'cp932_bin' => array(
            'charset' => 'cp932',
            'default' => false
        ),
        'eucjpms_japanese_ci' => array(
            'charset' => 'eucjpms',
            'default' => true
        ),
        'eucjpms_bin' => array(
            'charset' => 'eucjpms',
            'default' => false
        )
    );
    
    public static function getFieldSql($collation)
    {
        if(isset(self::$charset[$collation])){
            if(self::$charset[$collation]['default']){
                return "CHARACTER SET ".self::$charset[$collation]['charset'];
            }
            
            return "CHARACTER SET ".self::$charset[$collation]['charset']." COLLATE {$collation}";
        }
            
        return "";  
    }
    
    public static function getTableSql($collation, $tableCollation)
    {
        if($collation == $tableCollation){
            return "";
        }
        
        if(isset(self::$charset[$collation])){
            if(self::$charset[$collation]['default']){
                return "DEFAULT CHARSET=".self::$charset[$collation]['charset'];
            }
    
            return "DEFAULT CHARSET=".self::$charset[$collation]['charset']." COLLATE={$collation}";
        }
    
        return "";
    }
}
