<?//@more https://github.com/benparizek/html-email-grid-600 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?=$title?></title>

  <style type="text/css" media="screen">

    body, p, a,
    h1, h2, h3,
    h4, h5, h6  { margin:0; padding:0; }
    a:hover,
    a:active    { outline: none; }
    img         { border:0; line-height:100%; outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}

    #outlook a  { padding: 0; } /* Force Outlook to provide a "view in browser" button. */
    body        { width: 100% !important; } /* Force Hotmail to display emails at full width */
                .ReadMsgBody { width: 100%; }
                .ExternalClass { width: 100%; }

    body        { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
                            /* Prevent Webkit and Windows Mobile platforms from changing default font sizes. */

    table td    { border-collapse: collapse; } /* Fix Outlook 07, 10 Padding issue */

    /* Choose a grid based on your needs */
    /*
        GRID 3020
        Columns: 30px
        Gutters: 20px (Left & Right Gutters: 10px)
    */

    .col-3, .col-4, .col-6,
    .col-8, .col-9, .col-12 {
        padding-left:  10px;
        padding-right: 10px;
    }
    .col-3  { width: 130px; }
    .col-4  { width: 180px; }
    .col-6  { width: 280px; }
    .col-8  { width: 400px; }
    .col-9  { width: 430px; }
    .col-12 { width: 580px; }


    /* col-12-wow overrides col-12 class and creates a full 600px
       table with no padding.  This class can be used with any grid */
    .full-width {
      padding-left:  0;
      padding-right: 0;
      width: 600px;
    }


    #background-container { height:100% !important; margin:0; padding:0; width:100% !important; }

    /*  Set header tags, p tags and bulleted lists to have the same spacing
        use p tags for general text content, but not within bulleted lists.
        Text for bulleted lists can just remain wrapped in the td tags */
    h1, h2, h3,
    h4, h5, h6      { display:block; margin-top:12px; }
    td p            { margin-top: 12px; }
    td              { padding-bottom:6px; }
    td.bullet,
    td.bullet-item  { padding-top:12px; }

    /*  Set width of bullet column of a table-based bulleted list */
    td.bullet { width:20px; }


    /* Gmail/Hotmail add an extra space below images.  This fixes it. */
    .image-fix { display:block; }

    /* sets the global background color */
    body, #background-container {
      background-color: #ddd;
    }

    #template-container {
      background-color: #fff;
    }

    a {
      color: #0000FF;
    }

    /* Set your header font-styles here */
    h1, .h1 { font-family: arial; font-size:20px; line-height:22px; }
    /*
    h2, .h2 { font-family: arial; font-size:18px; line-height:20px; }
    h3, .h3 { font-family: arial; font-size:13px; line-height:17px; }
    h4, .h4 { ... }
    */

    /* Set your body fonts styles here */
    td, td p {
      font-family: arial;
      font-size:12px;
      line-height:18px;
    }

    /* if you need more font styles
         extend them with new font class */
    td.secondary-font-style,
    td.secondary-font-style p {
      font-family: arial;
      font-size:12px;
      line-height:18px;
    }


    @media only screen and (max-width: 480px) {

      td[class="background-container"] { padding:0 10px !important; }

      table[id="template-container"],
      table[class="mobile-friendly"] {
        width: 300px !important;
      }
      td[class="col-3"],
      td[class="col-4"],
      td[class="col-6"],
      td[class="col-8"],
      td[class="col-9"],
      td[class="col-12"],
      td[class="col-3"] img,
      td[class="col-4"] img,
      td[class="col-6"] img,
      td[class="col-8"] img,
      td[class="col-9"] img,
      td[class="col-12"] img,
      td[class="full-width"] {
          width: 300px !important;
      }

      img[class="showcase"]  {
          width: 320px !important;
      }

    }

  </style>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" leftmargin="0" offset="0">
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" id="background-container">
    <tr><td valign="top">


    <table width="600" align="center" border="0" cellspacing="0" cellpadding="0" id="template-container">
      <tr>
        <td valign="top" class="template-container">

<br />


<table align="left" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" class="col-12">
        <a href="<?=core::config('general.base_url')?>" target ="_blank" title="<?=core::config('general.site_name')?>">
                <?=core::config('general.site_name')?>
            </a>
          <h1><?=$title?></h1>
    </td>
  </tr>
  <tr>
    <td class="col-12">
        <?=$content?>
    </td>
  </tr>

</table>


<br />
        </td>
      </tr>
    </table>

    </td></tr></table>
  </body>
</html>