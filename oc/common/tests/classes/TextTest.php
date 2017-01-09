<?php defined('SYSPATH') or die('No direct access allowed!'); 
  
class TextTest extends Kohana_UnitTest_TestCase 
{ 


    public function provider_bb2html()
    {
        return array(
            array('[b]bold[/b]', '<b>bold</b>'),
            array('[i]cursive[/i]', '<i>cursive</i>'),
            array('[u]underline[/u]', '<u>underline</u>'),
            array('[s]strike[/s]', '<s>strike</s>'),
            array('[ul][li]item1[/li][li]item2[/li][/ul]', '<ul><li>item1</li><li>item2</li></ul>'),
            array('[ol][li]item1[/li][li]item2[/li][/ol]', '<ol><li>item1</li><li>item2</li></ol>'),
            array('[justify]justify[/justify]', '<div align="justify">justify</div>'),
            array('[table][tr][td]row 1, cell 1[/td][td]row 1, cell 2[/td][/tr][/table]', '<table><tr><td>row 1, cell 1</td><td>row 1, cell 2</td></tr></table>'),
            array('[center]center[/center]', '<div style="text-align: center;">center</div>'),
            array('[left]left[/left]', '<div style="text-align: left;">left</div>'),
            array('[right]right[/right]', '<div style="text-align: right;">right</div>'),
        );
    }

    public function provider_bb2html_advanced()
    {
        $adv = array(
                array('[color=red]color red[/color]', '<span style="color: red">color red</span>'),
                array('[font=Arial, Helvetica, sans]font family Arial ,color blue [/font]', '<font face="Arial, Helvetica, sans">font family Arial ,color blue </font>'),
                array('[size=9]size 9[/size]', '<span style="font-size: 9px">size 9</span>'),
                array('[quote]some quote[/quote]', '<blockquote>some quote</blockquote>'),
                array('[quote=some quote]some quote[/quote]', '<blockquote>some quote</blockquote>'),
                array('[url]http://open-classifieds.com[/url]', '<a rel="nofollow" target="_blank" href="http://open-classifieds.com">http://open-classifieds.com</a>'),
                array('[url=http://open-classifieds.com]oc[/url]', '<a rel="nofollow" target="_blank" href="http://open-classifieds.com">oc</a>'),
                array('[email]admin@open-classifieds.com[/email]', '<a href="mailto: admin@open-classifieds.com">admin@open-classifieds.com</a>'),
                array('[email=admin@open-classifieds.com]oc[/email]', '<a href="mailto: admin@open-classifieds.com">oc</a>'),
                array('[img]http://open-classifieds.com/logo.png[/img]', '<img src="http://open-classifieds.com/logo.png" alt="http://open-classifieds.com/logo.png" />'),
                array('[img=oc]http://open-classifieds.com/logo.png[/img]', '<img src="http://open-classifieds.com/logo.png" alt="oc" />'),
                array('[code]some code[/code]', '<code>some code</code>'),
                array('[youtube]http://www.youtube.com/watch?v=eTOKXCEwo_8[/youtube]',
                      '<iframe width="560" height="315" src="http://www.youtube.com/embed/eTOKXCEwo_8" frameborder="0" allowfullscreen></iframe>'),
                array('[youtube]eTOKXCEwo_8[/youtube]',
                      '<iframe width="560" height="315" src="http://www.youtube.com/embed/eTOKXCEwo_8" frameborder="0" allowfullscreen></iframe>'),
                
                );
        //concatenate another provider
        return array_merge($adv ,$this->provider_bb2html());
    }

    /**
     * @dataProvider provider_bb2html
     */
    public function test_bb2html($bbcode,$html) 
    {
        $this->assertEquals(Text::bb2html($bbcode),$html); 
    } 


    /**
     * @dataProvider provider_bb2html_advanced
     */
    public function test_bb2html_advanced($bbcode,$html) 
    {
        $this->assertEquals(Text::bb2html($bbcode,TRUE),$html); 
    } 


}