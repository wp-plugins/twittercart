<?php
//Plugin products page

$template = 
"
    <h1 style=\"display: block; width: 95%; margin: 20px auto;\" id=\"headertitle\">Products</h1>
    <div style=\"width: 95%; margin: 0 auto;\" id=\"mytcdfui\">
    <table id=\"products\" class=\"display tc_datatable\" cellspacing=\"0\" width=\"100%\" style=\"margin-top: 20px;\">
    <thead>
        <tr>
            <th onclick=\"setSort(this, 'tcthimgs');\" class=\"thimage tcthimgs\" style=\"width: 150px; height: 100px; background-color: #717a87; color: white;\"  onmouseover=\"setBothSort(this, 'tcthimgs');\" onmouseout=\"setReturnedSort(this, 'tcthimgs');\">Image<img class=\"sortable_img asc tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_asc.png\" style=\"display: inline; position: absolute;\"><img class=\"sortable_img desc tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_desc.png\"><img class=\"sortable_img both tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_both.png\"><img class=\"sortable_img allhover tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_hover.png\"></th>
            <th onclick=\"setSort(this, 'thprtcts');\" style=\"width: 150px; height: 100px; background-color: #717a87; color: white;\" class=\"thprtcts\"  onmouseover=\"setBothSort(this, 'thprtcts');\"  onmouseout=\"setReturnedSort(this, 'thprtcts');\">Product<img class=\"sortable_img asc thprtcts\" src=\"".TC_PLUGIN_URL."assets/images/sort_asc.png\" ><img class=\"sortable_img desc thprtcts\" src=\"".TC_PLUGIN_URL."assets/images/sort_desc.png\"><img class=\"sortable_img both thprtcts\" style=\"display: inline; position: absolute;\" src=\"".TC_PLUGIN_URL."assets/images/sort_both.png\"><img class=\"sortable_img allhover tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_hover.png\"></th>
            <th onclick=\"setSort(this, 'tcthprc');\"  class=\"tcthprc\" style=\"width: 150px; height: 100px; background-color: #717a87; color: white;\" onmouseover=\"setBothSort(this, 'tcthprc');\" onmouseout=\"setReturnedSort(this, 'tcthprc');\">Price<img class=\"sortable_img asc tcthprc\" src=\"".TC_PLUGIN_URL."assets/images/sort_asc.png\" ><img class=\"sortable_img desc tcthprc\" src=\"".TC_PLUGIN_URL."assets/images/sort_desc.png\"><img class=\"sortable_img both tcthprc\" style=\"display: inline; position: absolute;\" src=\"".TC_PLUGIN_URL."assets/images/sort_both.png\"><img class=\"sortable_img allhover tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_hover.png\"></th>
            <th onclick=\"setSort(this, 'tcthdt');\"  class=\"tcthdt\" style=\"width: 150px; height: 100px; background-color: #717a87; color: white;\" onmouseover=\"setBothSort(this, 'tcthdt');\" onmouseout=\"setReturnedSort(this, 'tcthdt');\">Date<img class=\"sortable_img asc tcthdt\" src=\"".TC_PLUGIN_URL."assets/images/sort_asc.png\" ><img class=\"sortable_img desc tcthdt\" src=\"".TC_PLUGIN_URL."assets/images/sort_desc.png\"><img class=\"sortable_img both tcthdt\" style=\"display: inline; position: absolute;\" src=\"".TC_PLUGIN_URL."assets/images/sort_both.png\"><img class=\"sortable_img allhover tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_hover.png\"></th>
            <th onclick=\"setSort(this, 'tcthcat');\"  class=\"tcthcat\" style=\"width: 150px; height: 100px; background-color: #717a87; color: white;\" onmouseover=\"setBothSort(this, 'tcthcat');\" onmouseout=\"setReturnedSort(this, 'tcthcat');\">Category<img class=\"sortable_img asc tcthcat\" src=\"".TC_PLUGIN_URL."assets/images/sort_asc.png\" ><img class=\"sortable_img desc tcthcat\" src=\"".TC_PLUGIN_URL."assets/images/sort_desc.png\"><img class=\"sortable_img both tcthcat\" style=\"display: inline; position: absolute;\" src=\"".TC_PLUGIN_URL."assets/images/sort_both.png\"><img class=\"sortable_img allhover tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_hover.png\"></th>
            <th onclick=\"setSort(this, 'tcthist');\"  class=\"tcthist\" style=\"width: 150px; height: 100px; background-color: #717a87; color: white;\" onmouseover=\"setBothSort(this, 'tcthist');\" onmouseout=\"setReturnedSort(this, 'tcthist');\">In stock<img class=\"sortable_img asc tcthist\" src=\"".TC_PLUGIN_URL."assets/images/sort_asc.png\" ><img class=\"sortable_img desc tcthist\" src=\"".TC_PLUGIN_URL."assets/images/sort_desc.png\"><img class=\"sortable_img both tcthist\" style=\"display: inline; position: absolute;\" src=\"".TC_PLUGIN_URL."assets/images/sort_both.png\"><img class=\"sortable_img allhover tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_hover.png\"></th>
            <th onclick=\"setSort(this, 'tcthtst');\"  class=\"tcthtst\" style=\"width: 150px; height: 100px; background-color: #717a87; color: white;\" onmouseover=\"setBothSort(this, 'tcthtst');\" onmouseout=\"setReturnedSort(this, 'tcthtst');\">Twitter status<img class=\"sortable_img asc tcthtst\" src=\"".TC_PLUGIN_URL."assets/images/sort_asc.png\" ><img class=\"sortable_img desc tcthtst\" src=\"".TC_PLUGIN_URL."assets/images/sort_desc.png\"><img class=\"sortable_img both tcthtst\" style=\"display: inline; position: absolute;\" src=\"".TC_PLUGIN_URL."assets/images/sort_both.png\"><img class=\"sortable_img allhover tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_hover.png\"></th>
            <th onclick=\"setSort(this, 'tcthact');\"  class=\"tcthact\" style=\"width: 150px; height: 100px; background-color: #717a87; color: white;\" onmouseover=\"setBothSort(this, 'tcthact');\" onmouseout=\"setReturnedSort(this, 'tcthact');\">Actions<img class=\"sortable_img asc tcthact\" src=\"".TC_PLUGIN_URL."assets/images/sort_asc.png\" ><img class=\"sortable_img desc tcthact\" src=\"".TC_PLUGIN_URL."assets/images/sort_desc.png\"><img class=\"sortable_img both tcthact\" style=\"display: inline; position: absolute;\" src=\"".TC_PLUGIN_URL."assets/images/sort_both.png\"><img class=\"sortable_img allhover tcthimgs\" src=\"".TC_PLUGIN_URL."assets/images/sort_hover.png\"></th>
        </tr>
    </thead>
    <tbody>
";
foreach ($products as $product){
    $template .= "
        <tr style=\"text-align: center; height: 100px;\">
            <td  style=\"font-weight: 200 !important;\"><a href=\"".$product['permalink']."\" style=\"text-decoration: none;\">".$product['image']."</a></td>
            <td style=\"font-weight: 200 !important;\"><a href=\"".$product['permalink']."\" style=\"text-decoration: none; color: #28b7e9;\">".$product['title']."</a></td>
            <td style=\"font-weight: 200 !important;\">".$product['price']."</td>
            <td style=\"font-weight: 200 !important;\">".$product['date']."</td>
            <td style=\"font-weight: 200 !important;\">".$product['category']."</td>
            <td style=\"font-weight: 200 !important;\">".$product['in_stock']."</td>
            ";
            if($product['twitter_status_link']){
                $template .= "<td class=\"tcpostedview\"><a href=\"".$product['twitter_status_link']."\" target=\"_blank\"  style=\"font-weight: 200 !important;\">Go to link</a></td>";
            }else {
                $template .= "<td class=\"tcpostview\" style=\"font-weight: 200 !important;\">Not posted</td>";
            }
    if(!$product['twitter_status_id']){
        $template .= "<td class=\"tcpostaction\" onclick=\"reloc('".$post_url.$product['id']."');\"><b><a href=\"".$post_url.$product['id']."\"  style=\"font-weight: 200 !important;\">Post to twitter</a></b></td>";
    }else{
        $template .= "<td class=\"tcpostedaction\"><b><span style=\"font-weight: 200 !important;\">Already posted</span></b></td>";
    }            
    $template .= "</tr>";
}
        
$template .= "</tbody></div></div></div></div>";
return $template;