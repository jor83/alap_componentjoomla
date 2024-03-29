<?php
/**
 * @package jDownloads
 * @version 2.5  
 * @copyright (C) 2007 - 2013 - Arno Betz - www.jdownloads.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * 
 * jDownloads is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */


 defined('_JEXEC') or die('Restricted access');

 setlocale(LC_ALL, 'C.UTF-8', 'C');
 
    global $jlistConfig;

    JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

    $db         = JFactory::getDBO(); 
    $document   = JFactory::getDocument();
    $jinput     = JFactory::getApplication()->input;
    $app        = JFactory::getApplication();    
    $user       = JFactory::getUser();

    $jdownloads_root_dir_name = basename($jlistConfig['files.uploaddir']);
        
    // get jD user limits and settings
    $jd_user_settings = JDHelper::getUserRules();
    
    // for Tabs
    jimport('joomla.html.pane');
    // for Tooltip
    JHtml::_('behavior.tooltip');
    // required for sub categories pagination
    JHtml::_('jquery.framework');

    
    $listOrder = str_replace('a.', '', $this->escape($this->state->get('list.ordering')));    
    $listDirn  = $this->escape($this->state->get('list.direction'));
    
    // Create shortcuts to some parameters.
    $params     = $this->category->params;
    $files      = $this->items;
    $category   = $this->category;
    $canEdit    = $this->category->params->get('access-create');

    $html                       = '';
    $body                       = '';
    $footer_text                = '';
    $layout                     = '';
    $cat_layout                 = '';
    $user_can_see_download_url = 0;
    $is_admin                   = false;
    
    $date_format = JDHelper::getDateFormat();

    if (JDHelper::checkGroup('8', true) || JDHelper::checkGroup('7', true)){
        $is_admin = true;
    }

    // Get the needed layout data - type = 1 for a 'categories' layout / Only used for the category 'subcategories'!           
    $cats_layout = JDHelper::getLayout(1, true);
    if ($cats_layout){
        // We need here not all fields
        $cats_before            = $cats_layout->template_before_text; 
        $cats_layout_text       = $cats_layout->template_text;
        $cats_after             = $cats_layout->template_after_text;
        $cats_use_subcategories = $cats_layout->use_to_view_subcats; 
    } else {
        // We have not a valid layout data
        echo '<big>No valid layout found for Categories!</big>';
    }

    // Get the needed layout data - type = 4 for a 'category' layout            
    $cat_layout = JDHelper::getLayout(4, false);
    if ($cat_layout){
        $cat_layout_before_text = $cat_layout->template_before_text;
        $cat_layout_text    = $cat_layout->template_text;
        $cat_after          = $cat_layout->template_after_text;
        $header             = $cat_layout->template_header_text;
        $subheader          = $cat_layout->template_subheader_text;
        $footer             = $cat_layout->template_footer_text;
    } else {
        // We have not a valid layout data
        echo '<big>No valid layout found for Category!</big>';
    }
    
    // Get the needed layout data - type = 2 for a 'files' layout            
    $layout_files = JDHelper::getLayout(2, false);
    if ($layout_files){
        $layout_files_text        = $layout_files->template_text;
        $layout_files_header      = $layout_files->template_header_text;
        $layout_files_subheader   = $layout_files->template_subheader_text;
        $layout_files_footer      = $layout_files->template_footer_text;
    } else {
        // We have not a valid layout data
        echo '<big>No valid layout found for files!</big>';
    }    
    
    
    if ($layout_files->symbol_off == 0 ) {
        $use_mini_icons = true;
    } else {
        $use_mini_icons = false; 
    }  

    // alternate CSS buttons when selected in configuration
    $status_color_hot       = $jlistConfig['css.button.color.hot'];
    $status_color_new       = $jlistConfig['css.button.color.new'];
    $status_color_updated   = $jlistConfig['css.button.color.updated'];
    $download_color         = $jlistConfig['css.button.color.download'];
    $download_size          = $jlistConfig['css.button.size.download'];
    $download_size_mirror   = $jlistConfig['css.button.size.download.mirror'];        
    $download_color_mirror1 = $jlistConfig['css.button.color.mirror1'];        
    $download_color_mirror2 = $jlistConfig['css.button.color.mirror2'];
    $download_size_listings = $jlistConfig['css.button.size.download.small'];    
    
    $catid                      = (int)$this->category->id;
    
    $total_downloads            = count($this->items);
    $total_subcategories        = count($this->children[$this->category->id]);
    
    // the amount of total files for all subcategoeries are stored in:
    // $this->children[$this->category->id][$i]->numitems;
    // the amount of total subcategories for this category (all levels) are stored in:
    // $this->children[$this->category->id][$i]->subcatitems;    
    
    if ($category->cat_dir_parent){
        $category_dir = $category->cat_dir_parent.'/'.$category->cat_dir;
    } else {
        $category_dir = $category->cat_dir;
    }    
    
    // get current category menu ID when exist and all needed menu IDs for the header links
    $menuItemids = JDHelper::getMenuItemids($catid);
    
    // get all other menu category IDs so we can use it when we needs it
    $cat_link_itemids = JDHelper::getAllJDCategoryMenuIDs();
    
    // Which use shall be use for "Overview" link item in header (type=all categories or a link to single category when it is existent as menu item)
    if ($this->params->get('use_type_all_categories_as_base_link')){
        $root_itemid = (int)$menuItemids['base'];
    } else {
        $root_itemid = (int)$menuItemids['root'];
    }        

    // make sure, that we have a valid menu itemid for the here viewed base category
    if (!$this->category->menu_itemid) $this->category->menu_itemid = $root_itemid;
        
    $html = '<div class="jd-item-page'.$this->pageclass_sfx.'">';
    
    if ($this->params->get('show_page_heading')) {
        $html .= '<h1>'.$this->escape($this->params->get('page_heading')).'</h1>';
    }         
     
    // ==========================================
    // HEADER SECTION
    // ==========================================

    if ($header != ''){
        
       
        // component title - not more used. So we must replace the placeholder from layout with spaces!
        $header = str_replace('{component_title}', '', $header);
        
        // replace google adsense placeholder with script when active (also for header tab)
        if ($jlistConfig['google.adsense.active'] && $jlistConfig['google.adsense.code'] != ''){
                $header = str_replace( '{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $header);
        } else {
                $header = str_replace( '{google_adsense}', '', $header);
        }        
        
        // components description
        if ($jlistConfig['downloads.titletext'] != '') {
            $header_text = stripslashes(JDHelper::getOnlyLanguageSubstring($jlistConfig['downloads.titletext']));
            if ($jlistConfig['google.adsense.active'] && $jlistConfig['google.adsense.code'] != ''){
                $header_text = str_replace( '{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $header_text);
            } else {
                $header_text = str_replace( '{google_adsense}', '', $header_text);
            }   
            $header .= $header_text;
        }
        
        // check $Itemid exist
        if (!isset($menuItemids['search'])) $menuItemids['search'] = $menuItemids['root'];
        if (!isset($menuItemids['upload'])) $menuItemids['upload'] = $menuItemids['root'];
        
        // build home link        
        $home_link = '<a href="'.JRoute::_('index.php?option=com_jdownloads&amp;Itemid='.$root_itemid).'">'.'<img src="'.JURI::base().'components/com_jdownloads/assets/images/home_fe.png" width="32" height="32" style="border:0px;" alt="'.JText::_('COM_JDOWNLOADS_HOME_LINKTEXT').'" /></a> <a href="'.JRoute::_('index.php?option=com_jdownloads&amp;Itemid='.$root_itemid).'">'.JText::_('COM_JDOWNLOADS_HOME_LINKTEXT').'</a>';
        // build search link
        $search_link = '<a href="'.JRoute::_('index.php?option=com_jdownloads&amp;view=search&amp;Itemid='.$menuItemids['search']).'">'.'<img src="'.JURI::base().'components/com_jdownloads/assets/images/search.png" width="32" height="32" style="border:0px;" alt="'.JText::_('COM_JDOWNLOADS_SEARCH_LINKTEXT').'" /></a> <a href="'.JRoute::_('index.php?option=com_jdownloads&amp;view=search&amp;Itemid='.$menuItemids['search'].'').'">'.JText::_('COM_JDOWNLOADS_SEARCH_LINKTEXT').'</a>';
        // build frontend upload link
        $upload_link = '<a href="'.JRoute::_('index.php?option=com_jdownloads&amp;view=form&amp;layout=edit&amp;catid='.$catid.'&amp;Itemid='.$menuItemids['upload']).'">'.'<img src="'.JURI::base().'components/com_jdownloads/assets/images/upload.png" width="32" height="32" style="border:0px;" alt="'.JText::_('COM_JDOWNLOADS_UPLOAD_LINKTEXT').'" /></a> <a href="'.JRoute::_('index.php?option=com_jdownloads&amp;view=form&amp;layout=edit&amp;catid='.$catid.'&amp;Itemid='.$menuItemids['upload'].'').'">'.JText::_('COM_JDOWNLOADS_UPLOAD_LINKTEXT').'</a>';

        $header = str_replace('{home_link}', $home_link, $header);
        $header = str_replace('{search_link}', $search_link, $header);

        if ($jd_user_settings->uploads_view_upload_icon){
            if ($this->view_upload_button){
                $header = str_replace('{upload_link}', $upload_link, $header);
            } else {
                $header = str_replace('{upload_link}', '', $header);
            }             
        } else {
            $header = str_replace('{upload_link}', '', $header);
        }    

        if ($menuItemids['upper'] > 1){   // 1 is 'root'
            // exists a single category menu link for the category a level up? 
            $level_up_cat_itemid = JDHelper::getSingleCategoryMenuID($cat_link_itemids, $menuItemids['upper'], $root_itemid);
            $upper_link = JRoute::_('index.php?option=com_jdownloads&amp;view=category&amp;catid='.$menuItemids['upper'].'&amp;Itemid='.$level_up_cat_itemid);
            $header = str_replace('{upper_link}', '<a href="'.$upper_link.'">'.'<img src="'.JURI::base().'components/com_jdownloads/assets/images/upper.png" width="32" height="32" style="border:0px;" alt="'.JText::_('COM_JDOWNLOADS_UPPER_LINKTEXT').'" /></a> <a href="'.$upper_link.'">'.JText::_('COM_JDOWNLOADS_UPPER_LINKTEXT').'</a>', $header);    
        } else {
            $upper_link = JRoute::_('index.php?option=com_jdownloads&amp;view=categories&amp;Itemid='.$menuItemids['base']);
            $header = str_replace('{upper_link}', '<a href="'.$upper_link.'">'.'<img src="'.JURI::base().'components/com_jdownloads/assets/images/upper.png" width="32" height="32" style="border:0px;" alt="'.JText::_('COM_JDOWNLOADS_UPPER_LINKTEXT').'" /></a> <a href="'.$upper_link.'">'.JText::_('COM_JDOWNLOADS_UPPER_LINKTEXT').'</a>', $header);            
        }
        
        // create category listbox and viewed it when it is activated in configuration
        if ($jlistConfig['show.header.catlist']){
            
            // get current selected cat id from listbox
            $catlistid = $jinput->get('catid', '0', 'integer');
            
            // get current sort order and direction
            $orderby_pri = $this->params->get('orderby_pri');
            // when empty get the state params
            $listordering = $this->state->get('list.ordering');
            if (!$orderby_pri && !empty($listordering)){             
                $state_ordering = $this->state->get('list.ordering');
                $state_direction = $this->state->get('list.direction');
                if ($state_ordering == 'c.title'){
                    if ($state_direction== 'DESC'){
                        $orderby_pri = 'ralpha';
                    } else {
                        $orderby_pri = 'alpha';
                    }  
                }    
            }             
            $data = JDHelper::buildCategorySelectBox($catlistid, $cat_link_itemids, $root_itemid, $jlistConfig['view.empty.categories'], $orderby_pri );            
            
            // build special selectable URLs for category listbox
            $root_url       = JRoute::_('index.php?option=com_jdownloads&Itemid='.$root_itemid);
            $uncat_url      = JRoute::_('index.php?option=com_jdownloads&view=downloads&type=uncategorised&Itemid='.$root_itemid);
            $allfiles_url   = JRoute::_('index.php?option=com_jdownloads&view=downloads&Itemid='.$root_itemid);
            $topfiles_url   = JRoute::_('index.php?option=com_jdownloads&view=downloads&type=top&Itemid='.$root_itemid);
            $newfiles_url   = JRoute::_('index.php?option=com_jdownloads&view=downloads&type=new&Itemid='.$root_itemid);
            
            
            $listbox = JHtml::_('select.genericlist', $data['options'], 'cat_list', 'class="inputbox" onchange="gocat(\''.$root_url.'\', \''.$uncat_url.'\', \''.$allfiles_url.'\', \''.$topfiles_url.'\',  \''.$newfiles_url.'\'  ,\''.$data['url'].'\')"', 'value', 'text', $data['selected'] ); 
            
            $header = str_replace('{category_listbox}', '<form name="go_cat" id="go_cat" method="post">'.$listbox.'</form>', $header);
        } else {                                                                        
            $header = str_replace('{category_listbox}', '', $header);         
        }
        
        $html .= $header;  

    }

    // ==========================================
    // SUB HEADER SECTION
    // ==========================================

    if ($subheader != ''){

        if ($jlistConfig['view.subheader']) {
            // display number of sub categories only when > 0 
            if ($total_subcategories == 0){
                $total_subcats_text = '';
            } else {
                $total_subcats_text = JText::_('COM_JDOWNLOADS_NUMBER_OF_SUBCATS_LABEL').': '.$total_subcategories;
            }
            
            // display category title
            $subheader = str_replace('{subheader_title}', JText::_('COM_JDOWNLOADS_FRONTEND_SUBTITLE_OVER_ONE_CAT').': '.$this->category->title, $layout_files_subheader);            

            // display pagination            
            if ($jlistConfig['option.navigate.top'] && $this->pagination->get('pages.total') > 1 && $this->params->get('show_pagination') != '0' 
                || (!$jlistConfig['option.navigate.top'] && $this->pagination->get('pages.total') > 1 && $this->params->get('show_pagination') == '1') )
            {            
                $page_navi_links = $this->pagination->getPagesLinks(); 
                if ($page_navi_links){
                    $page_navi_pages   = $this->pagination->getPagesCounter();
                    $page_navi_counter = $this->pagination->getResultsCounter(); 
                    $page_limit_box    = $this->pagination->getLimitBox();  
                }    
                $subheader = str_replace('{page_navigation}', $page_navi_links, $subheader);
                $subheader = str_replace('{page_navigation_results_counter}', $page_navi_counter, $subheader);
                if ($this->params->get('show_pagination_results') == null || $this->params->get('show_pagination_results') == '1'){
                    $subheader = str_replace('{page_navigation_pages_counter}', $page_navi_pages, $subheader); 
                } else {
                    $subheader = str_replace('{page_navigation_pages_counter}', '', $subheader);                
                }                                   
                $subheader = str_replace('{count_of_sub_categories}', $total_subcats_text, $subheader); 
            } else {
                $subheader = str_replace('{page_navigation}', '', $subheader);
                $subheader = str_replace('{page_navigation_results_counter}', '', $subheader);
                $subheader = str_replace('{page_navigation_pages_counter}', '', $subheader);                
                $subheader = str_replace('{count_of_sub_categories}', $total_subcats_text, $subheader);                
            }

            // display sort order bar
            if ($jlistConfig['view.sort.order'] && $total_downloads > 1 && $this->params->get('show_sort_order_bar') != '0'
            || (!$jlistConfig['view.sort.order'] && $this->pagination->get('pages.total') > 1 && $this->params->get('show_sort_order_bar') == '1') )
            {
               // we must have at minimum a single field for sorting
               $amount_sort_fields = explode(',', $jlistConfig['sortorder.fields']);
               if (count($amount_sort_fields))
               {
                   $limitstart = $this->pagination->limitstart;
                   
                   // create form
                   $sort_form = '<form action="'.htmlspecialchars(JFactory::getURI()->toString()).'" method="post" name="adminForm" id="adminForm">';
                   $sort_form_hidden = '<input type="hidden" name="filter_order" value="" />
                                       <input type="hidden" name="filter_order_Dir" value="" />
                                       <input type="hidden" name="limitstart" value="" /></form>';
                                  
                   $ordering = '<span class="jd-list-ordering" id="ordering1">'.JHtml::_('grid.sort', JText::_('COM_JDOWNLOADS_FE_SORT_ORDER_DEFAULT'), 'ordering', $listDirn, $listOrder).' | </span>';
                   $title    = '<span class="jd-list-title" id="ordering2">'.JHtml::_('grid.sort', JText::_('COM_JDOWNLOADS_FE_SORT_ORDER_NAME'), 'file_title', $listDirn, $listOrder).' | </span>';
                   $author   = '<span class="jd-list-author" id="ordering3">'.JHtml::_('grid.sort', JText::_('COM_JDOWNLOADS_FE_SORT_ORDER_AUTHOR'), 'author', $listDirn, $listOrder).' | </span>';               
                   $date     = '<span class="jd-list-date" id="ordering4">'.JHtml::_('grid.sort', JText::_('COM_JDOWNLOADS_FE_SORT_ORDER_DATE'), 'date_added', $listDirn, $listOrder).' | </span>';
                   $hits     = '<span class="jd-list-hits" id="ordering5">'.JHtml::_('grid.sort', JText::_('COM_JDOWNLOADS_FE_SORT_ORDER_HITS'), 'downloads', $listDirn, $listOrder).' | </span>';               
                   $featured = '<span class="jd-list-featured" id="ordering6">'.JHtml::_('grid.sort', JText::_('COM_JDOWNLOADS_FE_SORT_ORDER_FEATURED'), 'featured', $listDirn, $listOrder).' | </span>';
                   //$ratings  = '<span class="jd-list-ratings" id="ordering7">'.JHtml::_('grid.sort', JText::_('COM_JDOWNLOADS_FE_SORT_ORDER_RATINGS'), 'downloads', $listDirn, $listOrder).' | </span>';               

                   $listorder_bar = $sort_form
                                    .JText::_('COM_JDOWNLOADS_FE_SORT_ORDER_TITLE').' '
                                    .'<br />';
                                    
                   foreach ($amount_sort_fields as $sfield) {
                        switch ($sfield) {
                            case 0:
                                $listorder_bar = $listorder_bar.$ordering;
                                break;
                            case 1:
                                $listorder_bar = $listorder_bar.$title;
                                break;
                            case 2:
                                $listorder_bar = $listorder_bar.$author;
                                break;
                            case 3:
                                $listorder_bar = $listorder_bar.$date;
                                break;
                            case 4:
                                $listorder_bar = $listorder_bar.$hits;
                                break;
                            case 5:
                                $listorder_bar = $listorder_bar.$featured;
                                break;
                            /*case 6:
                                $listorder_bar = $listorder_bar.$ratings;
                                break; */                                                                                                                               
                        }
                   }
                   // remove | at the end
                   $len = strlen($listorder_bar);
                   $pos = strripos($listorder_bar, "|");
                   $diff = $len - $pos;
                   if ($pos > 0 && $diff == 9){
                       $listorder_bar = substr($listorder_bar, 0, ($len - $diff)).'</span>';  
                   } 
                   // add hidden fields
                   $listorder_bar = $listorder_bar.$sort_form_hidden;
                                      
                   $subheader = str_replace('{sort_order}', $listorder_bar, $subheader);
               } else {
                   $subheader = str_replace('{sort_order}', '', $subheader);          
               }
            } else {   
               $subheader = str_replace('{sort_order}', '', $subheader);          
            }    
        }    
        // remove this placeholder when it is used not for files layout
        $subheader = str_replace('{sort_order}', '', $subheader); 
        
        // replace google adsense placeholder with script when active (also for subheader tab)
        if ($jlistConfig['google.adsense.active'] && $jlistConfig['google.adsense.code'] != ''){
                $subheader = str_replace( '{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $subheader);
        } else {
                $subheader = str_replace( '{google_adsense}', '', $subheader);
        }         
 
        $html .= $subheader;            
    }
    
    // ==========================================
    // BODY SECTION - VIEW THE SELECTED CATEGORY DATA
    // ==========================================
    
    if ($cat_layout_text != ''){
 
        $body_cat = $cat_layout_before_text;
        $body_cat .= $cat_layout_text;
   
        // ===================
        // Category Data
        // ===================
   
        // get category pic
        if ($this->category->pic != '' ) {
            $catpic = '<img src="'.JURI::base().'images/jdownloads/catimages/'.$this->category->pic.'" style="text-align:top;border:0px;" width="'.$jlistConfig['cat.pic.size'].'" height="'.$jlistConfig['cat.pic.size.height'].'" alt="'.$this->category->pic.'" /> ';
        } else {
            $catpic = '';
        }

        // display category info  - but make sure, that this option only used with single column layouts
        if ($jlistConfig['view.category.info']){
            $body_cat = str_replace('{cat_title}', $this->category->title, $body_cat);

            // support for content plugins
            if ($jlistConfig['activate.general.plugin.support'] && $jlistConfig['use.general.plugin.support.only.for.descriptions']) {  
               $this->category->description = JHtml::_('content.prepare', $this->category->description);
            }

            $show_description = $this->params->get('show_description');
            if (isset($show_description)){
                if ($show_description){
                    $body_cat = str_replace('{cat_description}', $this->category->description, $body_cat);
                } else {
                    $body_cat = str_replace('{cat_description}', '', $body_cat);
                }
            } else {
                $body_cat = str_replace('{cat_description}', $this->category->description, $body_cat);
            }    
            $body_cat = str_replace('{cat_id}', $this->category->id, $body_cat);
            $body_cat = str_replace('{cat_pic}', $catpic, $body_cat);
            $body_cat = str_replace('{sum_subcats}', '', $body_cat);
            $body_cat = str_replace('{sum_files_cat}', JText::_('COM_JDOWNLOADS_FRONTEND_COUNT_FILES').' '.$total_downloads, $body_cat);
            $body_cat = str_replace('{cat_info_begin}', '', $body_cat); 
            $body_cat = str_replace('{cat_info_end}', '', $body_cat);
            
            if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)){
                $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); 
                $body_cat = str_replace('{tags}', $this->category->tagLayout->render($this->category->tags->itemTags), $body_cat);
                $body_cat = str_replace('{tags_title}', JText::_('COM_JDOWNLOADS_TAGS_LABEL'), $body_cat);    
            } else {
                $body_cat = str_replace('{tags}', '', $body_cat);
                $body_cat = str_replace('{tags_title}', '', $body_cat); 
            }
            
            // remove all title html tags in top cat output
            if ($pos_end = strpos($body_cat, '{cat_title_end}')){
                $pos_beg = strpos($body_cat, '{cat_title_begin}');
                $body_cat = substr_replace($body_cat, '', $pos_beg, ($pos_end - $pos_beg) + 15);
            }

        }  else {
            
            // display not a category info 
            $body_cat = str_replace('{cat_title}', '', $body_cat);
            $body_cat = str_replace('{tags}', '', $body_cat);
            $body_cat = str_replace('{tags_title}', '', $body_cat);
            
            // remove all title html tags in top cat output
            if ($pos_end = strpos($body_cat, '{cat_title_end}')){
                $pos_beg = strpos($body_cat, '{cat_title_begin}');
                $body_cat = substr_replace($body_cat, '', $pos_beg, ($pos_end - $pos_beg) + 15);
            } 
            
            // remove all html tags in top cat output
            if ($pos_end = strpos($body_cat, '{cat_info_end}')){
                $pos_beg = strpos($body_cat, '{cat_info_begin}');
                $body_cat = substr_replace($body_cat, '', $pos_beg, ($pos_end - $pos_beg) + 14);
            } else {
                $body_cat = str_replace('{cat_description}', '', $body_cat);
                $body_cat = str_replace('{cat_pic}', '', $body_cat);
                $body_cat = str_replace('{sum_subcats}', '', $body_cat);
                $body_cat = str_replace('{sum_files_cat}', '', $body_cat);
            }
        }
        
        // google adsense
        if ($jlistConfig['google.adsense.active']){
            $body_cat = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $body_cat);
        } else {
            $body_cat = str_replace('{google_adsense}', '', $body_cat);
        }
        
        $body_cat .= $cat_after; 
       
        // ===================
        // Sub Categories Data
        // ===================
                
        // build the data for the javascript pagination 
        $labelprev  = JText::_('COM_JDOWNLOADS_JS_PAGINATION_PREV');
        $labelnext  = JText::_('COM_JDOWNLOADS_JS_PAGINATION_NEXT');
        $labelstart = JText::_('COM_JDOWNLOADS_JS_PAGINATION_START');
        $labelend   = JText::_('COM_JDOWNLOADS_JS_PAGINATION_END');
        $labelnumber = JText::_('COM_JDOWNLOADS_JS_PAGINATION_AMOUNT');
        $labelpage  = JText::_('COM_JDOWNLOADS_FRONTEND_HEADER_PAGENAVI_PAGE_TEXT');
        $labelof    = JText::_('COM_JDOWNLOADS_FRONTEND_HEADER_PAGENAVI_TO_TEXT');
        
        if (!isset($show_empty_categories)){
            $show_empty_categories = false;
        }        

        $i             = 0;
        $w             = 0;
        $paging        = '';
        $subcat_itemid = '';
                
        if($total_subcategories > 0){
            
            $body_subcats = $cats_before;
            
            $parentCatid = $this->category->id;
            $max_cats = count($this->children[$this->category->id]);
             
            for ($i=0; $i < $total_subcategories; $i++){
            
                $body_subcats .= $cats_layout_text;
                
                // check whether we must build the output
                if ($show_empty_categories || $jlistConfig['view.empty.categories'] || $this->children[$parentCatid][$i]->getNumItems(true) || count($this->children[$parentCatid][$i]->getChildren())){            
            
                    // exists a single category menu link for this subcat? 
                    if ($this->children[$parentCatid][$i]->menu_itemid){
                        $subcat_itemid =  (int)$this->children[$parentCatid][$i]->menu_itemid;
                    } else {
                        $subcat_itemid = $root_itemid;
                    }    
                     
                    //display cat info
                     $catlink = JRoute::_("index.php?option=com_jdownloads&amp;view=category&amp;catid=".$this->children[$parentCatid][$i]->id."&amp;Itemid=".$subcat_itemid);                        
                     
                     // display category symbol/pic 
                     if ($this->children[$parentCatid][$i]->pic != '' ) {
                         $catpic = '<a href="'.$catlink.'"><img src="'.JURI::base().'images/jdownloads/catimages/'.$this->children[$parentCatid][$i]->pic.'" style="text-align:top;border:0px;" width="'.$jlistConfig['cat.pic.size'].'" height="'.$jlistConfig['cat.pic.size.height'].'" alt="'.$this->children[$parentCatid][$i]->pic.'" /></a> ';
                     } else {
                         $catpic = '';
                     }
                     
                     if ($this->params->get('show_cat_tags', 1) && !empty($this->children[$parentCatid][$i]->tags->itemTags)){
                         $this->children[$parentCatid][$i]->tagLayout = new JLayoutFile('joomla.content.tags'); 
                         $body_subcats = str_replace('{tags}', $this->children[$parentCatid][$i]->tagLayout->render($this->children[$parentCatid][$i]->tags->itemTags), $body_subcats);
                         $body_subcats = str_replace('{tags_title}', JText::_('COM_JDOWNLOADS_TAGS_LABEL'), $body_subcats);
                            
                     } else {
                         $body_subcats = str_replace('{tags}', '', $body_subcats);
                         $body_subcats = str_replace('{tags_title}', '', $body_subcats);
                     }                                              

                     // more as one column   ********************************************************
                     if ($cats_layout->cols > 1 && strpos($cats_layout_text, '{cat_title1}')){
                        $a = 0;     
                        
                        for ($a=0; $a < $cats_layout->cols; $a++){

                            if ($a >= $total_subcategories || $i == $total_subcategories || $w == $total_subcategories){
                                continue;
                            }
    
                            // exists a single category menu link for this subcat? 
                            if ($this->children[$parentCatid][$i]->menu_itemid){
                                $subcat_itemid =  (int)$this->children[$parentCatid][$i]->menu_itemid;
                            } else {
                                $subcat_itemid = $root_itemid;
                            }                        
                            
                            //display cat info
                            $catlink = JRoute::_("index.php?option=com_jdownloads&amp;view=category&amp;catid=".$this->children[$parentCatid][$i]->id."&amp;Itemid=".$subcat_itemid);
                            
                            // Symbol anzeigen - auch als url                                                                                                                             
                            if ($this->children[$parentCatid][$i]->pic != '' ) {
                                $catpic = '<a href="'.$catlink.'"><img src="'.JURI::base().'images/jdownloads/catimages/'.$this->children[$parentCatid][$i]->pic.'" style="text-align:top;border:0px;" width="'.$jlistConfig['cat.pic.size'].'" height="'.$jlistConfig['cat.pic.size.height'].'" alt="'.$this->children[$parentCatid][$i]->pic.'" /></a> ';
                            } else {
                                $catpic = '';
                            }                     
                           
                             $x = $a + 1;
                             $x = (string)$x;

                             if ($i < count($this->children[$this->category->id])){
                                if ($a == 0){
                                    $body_subcats = str_replace("{cat_title$x}", '<a href="'.$catlink.'">'.$this->children[$parentCatid][$i]->title.'</a>', $body_subcats);
                                } else {
                                    $body_subcats = str_replace("{cat_title$x}", '<a href="'.$catlink.'">'.$this->children[$parentCatid][$i]->title.'</a>', $body_subcats);
                                }
                                 
                                $body_subcats = str_replace("{cat_pic$x}", $catpic, $body_subcats);

                                if ($this->params->get('show_subcat_desc')){
                                    $body_subcats = str_replace("{cat_description$x}", $this->children[$parentCatid][$i]->description, $body_subcats);
                                } else {
                                   $body_subcats = str_replace("{cat_description$x}", '', $body_subcats); 
                                }
                                    
                                $body_subcats = str_replace("{sum_subcats$x}", JText::_('COM_JDOWNLOADS_FRONTEND_COUNT_SUBCATS').' '.$this->children[$parentCatid][$i]->subcatitems, $body_subcats);
                                $body_subcats = str_replace("{sum_files_cat$x}", JText::_('COM_JDOWNLOADS_FRONTEND_COUNT_FILES').' '.$this->children[$parentCatid][$i]->numitems, $body_subcats);
                             
                             } else {
                                
                                $body_subcats = str_replace("{cat_title$x}", '', $body_subcats);
                                $body_subcats = str_replace("{cat_pic$x}", '', $body_subcats);
                                $body_subcats = str_replace("{cat_description$x}", '', $body_subcats);
                             }
                             $w = $i+1;
                             if (($a + 1) < $cats_layout->cols && isset($this->children[$parentCatid][($w)])){
                                $i++;

                                // exists a single category menu link for this subcat? 
                                if ($this->children[$parentCatid][$i]->menu_itemid){
                                    $subcat_itemid =  (int)$this->children[$parentCatid][$i]->menu_itemid;
                                } else {
                                    $subcat_itemid = $root_itemid;
                                }
                                                             
                                $catlink = JRoute::_("index.php?option=com_jdownloads&amp;view=category&amp;catid=".$this->children[$parentCatid][$i]->id."&amp;Itemid=".$subcat_itemid);
                                
                                // Symbol anzeigen - auch als url                                                                                                                    
                                if ($this->children[$parentCatid][$i]->pic != '' ) {
                                    $catpic = '<a href="'.$catlink.'"><img src="'.JURI::base().'images/jdownloads/catimages/'.$this->children[$parentCatid][$i]->pic.'" style="text-align:top;border:0px;" width="'.$jlistConfig['cat.pic.size'].'" height="'.$jlistConfig['cat.pic.size.height'].'" alt="'.$this->children[$parentCatid][$i]->pic.'" /></a> ';
                                } else {
                                    $catpic = '';
                                }
                             }  
                        }
                        
                        for ($b=1; $b < 10; $b++){
                            $x = (string)$b;
                            $body_subcats = str_replace("{cat_title$x}", '', $body_subcats);
                            $body_subcats = str_replace("{cat_pic$x}", '', $body_subcats);
                            $body_subcats = str_replace("{sum_files_cat$x}", '', $body_subcats); 
                            $body_subcats = str_replace("{sum_subcats$x}", '', $body_subcats);
                            $body_subcats = str_replace("{cat_description$x}", '', $body_subcats);
                        }
                     
                     } else {
                        
                         $body_subcats = str_replace('{cat_title}', '<a href="'.$catlink.'">'.$this->children[$parentCatid][$i]->title.'</a>', $body_subcats);
                         if ($this->children[$parentCatid][$i]->subcatitems == 0){
                            $body_subcats = str_replace('{sum_subcats}','', $body_subcats);
                         } else {
                            $body_subcats = str_replace('{sum_subcats}', JText::_('COM_JDOWNLOADS_FRONTEND_COUNT_SUBCATS').' '.$this->children[$parentCatid][$i]->subcatitems, $body_subcats);
                         }
                         $body_subcats = str_replace('{sum_files_cat}', JText::_('COM_JDOWNLOADS_FRONTEND_COUNT_FILES').' '.$this->children[$parentCatid][$i]->numitems, $body_subcats);
                     }
                       
                     if ($this->params->get('show_subcat_desc')){
                        $body_subcats = str_replace('{cat_description}', $this->children[$parentCatid][$i]->description, $body_subcats);
                     } else {
                         $body_subcats = str_replace('{cat_description}', '', $body_subcats); 
                     }    
                     
                     $body_subcats = str_replace('{cat_pic}', $catpic, $body_subcats);
                     $body_subcats = str_replace('{cat_info_begin}', '', $body_subcats); 
                     $body_subcats = str_replace('{cat_info_end}', '', $body_subcats);
                     if ($i > 0){
                         // remove all title html tags in top categories output
                         if ($pos_end = strpos($body_subcats, '{cat_title_end}')){
                            $pos_beg = strpos($body_subcats, '{cat_title_begin}');
                            $body_subcats = substr_replace($body_subcats, '', $pos_beg, ($pos_end - $pos_beg) + 15);
                         } 
                     } else {
                         $body_subcats = str_replace('{subcats_title_text}', JText::_('COM_JDOWNLOADS_FE_FILELIST_TITLE_OVER_SUBCATS_LIST'), $body_subcats);             
                         $body_subcats = str_replace('{cat_title_begin}', '', $body_subcats); 
                         $body_subcats = str_replace('{cat_title_end}', '', $body_subcats);
                     }
                 } else {
                    // We have an empty category - so we need not any layout for this category
                    $body_subcats = '';
                     
                     $max_cats --;

                 } 
            }
            $body_subcats = str_replace('{files}', '', $body_subcats);
            $body_subcats = str_replace('{checkbox_top}', '', $body_subcats);
            $body_subcats = str_replace('{form_hidden}', '', $body_subcats);
            $body_subcats = str_replace('{form_button}', '', $body_subcats);
            
            $body_subcats .= $cats_after;
            
            if ($jlistConfig['use.pagination.subcategories'] && ($total_subcategories > (int)$jlistConfig['amount.subcats.per.page.in.pagination'])){
                // add pagination 
               if (strpos($cats_layout->template_after_text, '</table') !== false){
                   // old layout with tables
                   $body_subcats .= '<script type="text/javascript">
                       '."
                       var pager = new Pager('results',".(int)$jlistConfig['amount.subcats.per.page.in.pagination'].",'pager','pageNavPosition','".$labelprev."','".$labelnext."','".$labelstart."','".$labelend."','".$labelnumber."','".$total_subcategories."'); 
                       pager.init(); 
                       pager.showPage(1);
                   </script>";
               } else {               
                   // new divs layout
                   $body_subcats .= '<script type="text/javascript">
                       '."
                       var pager = new jddiv.Pager();
                       jQuery(document).ready(function() {
                            pager.paragraphsPerPage = ".(int)$jlistConfig['amount.subcats.per.page.in.pagination']."; // set amount elements per page
                            pager.pagingContainer = jQuery('#results'); // set of main container
                            pager.paragraphs = jQuery('div.jd_subcat_pagination_inner_wrapper', pager.pagingContainer); // set of required containers
                            pager.labelPage = '".$labelpage."';
                            pager.labelOf   = '".$labelof."';
                            pager.showPage(1);
                            });
                       </script>";
               }
            }      
            
            // google adsense
            if ($jlistConfig['google.adsense.active']){
                $body_subcats = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $body_subcats);
            } else {
                $body_subcats = str_replace('{google_adsense}', '', $body_subcats);
            }

            // support for content plugins 
            if ($jlistConfig['activate.general.plugin.support'] && !$jlistConfig['use.general.plugin.support.only.for.descriptions']) {
                $body_subcats = JHtml::_('content.prepare', $body_subcats);
            }
            
            $body_cat = str_replace('{sub_categories}', $body_subcats, $body_cat);
        } else {
            $body_cat = str_replace('{sub_categories}', '', $body_cat);
        }                     
    }

    $formid = $this->category->id;

    // ===================
    // Downloads List
    // ===================

    $html_files = '';

    if ($layout_files_text != ''){
        
        // build the mini image symbols when used in layout ( 0 = activated !!! )
        if ($use_mini_icons) {
            $msize =  $jlistConfig['info.icons.size'];
            $pic_date = '<img src="'.JURI::base().'images/jdownloads/miniimages/date.png" style="text-align:middle;border:0px;" width="'.$msize.'" height="'.$msize.'"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DATE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DATE').'" />&nbsp;';
            $pic_license = '<img src="'.JURI::base().'images/jdownloads/miniimages/license.png" style="text-align:middle;border:0px;" width="'.$msize.'" height="'.$msize.'"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LICENCE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LICENCE').'" />&nbsp;';
            $pic_author = '<img src="'.JURI::base().'images/jdownloads/miniimages/contact.png" style="text-align:middle;border:0px;" width="'.$msize.'" height="'.$msize.'"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_AUTHOR').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_AUTHOR').'" />&nbsp;';
            $pic_website = '<img src="'.JURI::base().'images/jdownloads/miniimages/weblink.png" style="text-align:middle;border:0px;" width="'.$msize.'" height="'.$msize.'"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_WEBSITE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_WEBSITE').'" />&nbsp;';
            $pic_system = '<img src="'.JURI::base().'images/jdownloads/miniimages/system.png" style="text-align:middle;border:0px;" width="'.$msize.'" height="'.$msize.'"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_SYSTEM').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_SYSTEM').'" />&nbsp;';
            $pic_language = '<img src="'.JURI::base().'images/jdownloads/miniimages/language.png" style="text-align:middle;border:0px;" width="'.$msize.'" height="'.$msize.'"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LANGUAGE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LANGUAGE').'" />&nbsp;';
            $pic_downloads = '<img src="'.JURI::base().'images/jdownloads/miniimages/download.png" style="text-align:middle;border:0px;" width="'.$msize.'" height="'.$msize.'"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DOWNLOAD_HITS').'" />&nbsp;';
            $pic_price = '<img src="'.JURI::base().'images/jdownloads/miniimages/currency.png" style="text-align:middle;border:0px;" width="'.$msize.'" height="'.$msize.'"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_PRICE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_PRICE').'" />&nbsp;';
            $pic_size = '<img src="'.JURI::base().'images/jdownloads/miniimages/stuff.png" style="text-align:middle;border:0px;" width="'.$msize.'" height="'.$msize.'"  alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_FILESIZE').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_FILESIZE').'" />&nbsp;';
        } else {
            $pic_date = '';
            $pic_license = '';
            $pic_author = '';
            $pic_website = '';
            $pic_system = '';
            $pic_language = '';
            $pic_downloads = '';
            $pic_price = '';
            $pic_size = '';
        }

        // create the pics for: NEW file / HOT file / file is UPDATED
        $hotpic = '<img src="'.JURI::base().'images/jdownloads/hotimages/'.$jlistConfig['picname.is.file.hot'].'" alt="hotpic" />';
        $newpic = '<img src="'.JURI::base().'images/jdownloads/newimages/'.$jlistConfig['picname.is.file.new'].'" alt="newpic" />';
        $updatepic = '<img src="'.JURI::base().'images/jdownloads/updimages/'.$jlistConfig['picname.is.file.updated'].'" alt="updatepic" />';
        
        // build a little pic for extern links
        $extern_url_pic = '<img src="'.JURI::base().'components/com_jdownloads/assets/images/link_extern.gif" alt="external" />';        
        
        // ===========================================
        // display now the categories files (downloads)
        
        // check at first whether we have at minimum one Download with a downloadable file
        // otherwise we need not the top and footer checkbox part for multi download layouts
        $amount_downloads_with_file = 0;
        for ($i = 0; $i < count($files); $i++) {
            if ($files[$i]->url_download){
                $amount_downloads_with_file ++;
            }
        }
            
        // start now with the Downloads    
        for ($i = 0; $i < count($files); $i++) {
            
            // When user has access: get data to publish the edit icon and publish data as tooltip
            if ($files[$i]->params->get('access-edit')){
                $editIcon = JDHelper::getEditIcon($files[$i]);
            } else {
                $editIcon = '';
            }
            
            $has_no_file = false;
            $file_id = $files[$i]->file_id;

            // when we have not a menu item to the singel download, we need a menu item from the assigned category, or at lates the root itemid
            if ($files[$i]->menuf_itemid){
                $file_itemid =  (int)$files[$i]->menuf_itemid;
            } else {
                $file_itemid = $this->category->menu_itemid;
            }             

            // checkbox is only viewed, when we have not an external file - and not only a document
            if (!$files[$i]->extern_file && ($files[$i]->other_file_id || $files[$i]->url_download)){
                // we can view the download link only when user can download it
                if ($files[$i]->params->get('access-download') == true){                
                    $checkbox_list = '<input type="checkbox" id="cb'.$i.'" name="cb_arr[]" value="'.$file_id.'" onclick="istChecked(this.checked,'.$formid.');"/>';
                } else {
                    // we will give the user a hint 
                    $userinfo = JText::_('COM_JDOWNLOADS_FRONTEND_FILE_NO_PERMISSIONS');
                    $checkbox_list = JHtml::_('tooltip', $userinfo, '', JURI::base().'components/com_jdownloads/assets/images/tooltip.png' );
                }    
            } else {
                if (!$files[$i]->url_download && !$files[$i]->other_file_id && !$files[$i]->extern_file){
                    // only a document without file
                    $userinfo = JText::_('COM_JDOWNLOADS_FRONTEND_ONLY_DOCUMENT_USER_INFO');
                    $has_no_file = true;
                } else {
                    // external file 
                    $userinfo = JText::_('COM_JDOWNLOADS_FRONTEND_EXTERN_FILE_USER_INFO');
                }    
                $checkbox_list = JHtml::_('tooltip', $userinfo, '', JURI::base().'components/com_jdownloads/assets/images/tooltip.png' );
            }    
            
            // add the content plugins event
            $event = $files[$i]->event->beforeDisplayContent;
            // get the activated/selected "files" layout text to build the output for every download
            $html_file = str_replace('{file_id}',$files[$i]->file_id, $event.$layout_files_text);
            
            // replace 'featured' placeholders
            if ($files[$i]->featured){
                // add the css class
                $html_file = str_replace('{featured_class}', 'jd_featured', $html_file);
                $html_file = str_replace('{featured_detail_class}', 'jd_featured_detail', $html_file);
                // add the pic
                if ($jlistConfig['featured.pic.filename']){
                    $featured_pic = '<img class="jd_featured_star" src="'.JURI::base().'images/jdownloads/featuredimages/'.$jlistConfig['featured.pic.filename'].'" width="'.$jlistConfig['featured.pic.size'].'" height="'.$jlistConfig['featured.pic.size.height'].'" alt="'.$jlistConfig['featured.pic.filename'].'" />';
                    $html_file = str_replace('{featured_pic}', $featured_pic, $html_file);
                } else {
                    $html_file = str_replace('{featured_pic}', '', $html_file);
                }
            } else {
                $html_file = str_replace('{featured_class}', '', $html_file);
                $html_file = str_replace('{featured_detail_class}', '', $html_file);
                $html_file = str_replace('{featured_pic}', '', $html_file);
            }
            
            if ($this->params->get('show_tags', 1) && !empty($files[$i]->tags->itemTags)){
                $files[$i]->tagLayout = new JLayoutFile('joomla.content.tags'); 
                $html_file = str_replace('{tags}', $files[$i]->tagLayout->render($files[$i]->tags->itemTags), $html_file);   
                $html_file = str_replace('{tags_title}', JText::_('COM_JDOWNLOADS_TAGS_LABEL'), $html_file);   
            } else {
                $html_file = str_replace('{tags}', '', $html_file);
                $html_file = str_replace('{tags_title}', '', $html_file);
            }            
            
            // files title row info only view when it is the first file
            if ($i > 0){
                // remove all html tags in top cat output
                if ($pos_end = strpos($html_file, '{files_title_end}')){
                    $pos_beg = strpos($html_file, '{files_title_begin}');
                    $html_file = substr_replace($html_file, '', $pos_beg, ($pos_end - $pos_beg) + 17);
                }
            } else {
                $html_file = str_replace('{files_title_text}', JText::_('COM_JDOWNLOADS_FE_FILELIST_TITLE_OVER_FILES_LIST'), $html_file);
                $html_file = str_replace('{files_title_end}', '', $html_file);
                $html_file = str_replace('{files_title_begin}', '', $html_file);
            } 
     
             // create file titles
             $html_file = JDHelper::buildFieldTitles($html_file, $files[$i]);
            
            // create filename
            if ($files[$i]->url_download){
                $html_file = str_replace('{file_name}', JDHelper::getShorterFilename($files[$i]->url_download), $html_file);
            } elseif (isset($files[$i]->filename_from_other_download) && $files[$i]->filename_from_other_download != ''){    
                $html_file = str_replace('{file_name}', JDHelper::getShorterFilename($files[$i]->filename_from_other_download), $html_file);
            } else {
                $html_file = str_replace('{file_name}', '', $html_file);
            }
            
             // google adsense
             if ($jlistConfig['google.adsense.active']){
                 $html_file = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $html_file);
             } else {
                 $html_file = str_replace('{google_adsense}', '', $html_file);
             } 

             // report download link
             if ($jd_user_settings->view_report_form){
                $report_link = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;view=report&amp;id=".$files[$i]->slug."&amp;catid=".$files[$i]->cat_id."&amp;Itemid=".$root_itemid).'" rel="nofollow">'.JText::_('COM_JDOWNLOADS_FRONTEND_REPORT_FILE_LINK_TEXT').'</a>';
                $html_file = str_replace('{report_link}', $report_link, $html_file);
             } else {
                $html_file = str_replace('{report_link}', '', $html_file);
             }
            
             // view sum comments 
             if ($jlistConfig['view.sum.jcomments'] && $jlistConfig['jcomments.active']){
                 // check that comments table exist - get DB prefix string
                 $prefix = $db->getPrefix();
                 // sometimes wrong uppercase prefix result string - so we fix it
                 $prefix2 = strtolower($prefix);
                 $tablelist = $db->getTableList();
                 if (in_array($prefix.'jcomments', $tablelist ) || in_array($prefix2.'jcomments', $tablelist )){
                     $db->setQuery('SELECT COUNT(*) from #__jcomments WHERE object_group = \'com_jdownloads\' AND object_id = '.$files[$i]->file_id);
                     $sum_comments = $db->loadResult();
                     if ($sum_comments >= 0){
                         $comments = sprintf(JText::_('COM_JDOWNLOADS_FRONTEND_JCOMMENTS_VIEW_SUM_TEXT'), $sum_comments); 
                         $html_file = str_replace('{sum_jcomments}', $comments, $html_file);
                     } else {
                        $html_file = str_replace('{sum_jcomments}', '', $html_file);
                     }
                 } else {
                     $html_file = str_replace('{sum_jcomments}', '', $html_file);
                 }    
             } else {   
                 $html_file = str_replace('{sum_jcomments}', '', $html_file);
             }    

            if ($files[$i]->release == '' ) {
                $html_file = str_replace('{release}', '', $html_file);
            } else {
                $html_file = str_replace('{release}', $files[$i]->release, $html_file);
            }

            $html_file = str_replace('{category_title}', JText::_('COM_JDOWNLOADS_CATEGORY_LABEL'), $html_file);
            $html_file = str_replace('{category_name}', $category->title, $html_file);
            
            // display the thumbnails
            $html_file = JDHelper::placeThumbs($html_file, $files[$i]->images, 'list');                                                    

            // support for content plugins in description / here in the files list layout is only used the short description
            if ($jlistConfig['activate.general.plugin.support'] && $jlistConfig['use.general.plugin.support.only.for.descriptions']) {  
                $files[$i]->description = JHtml::_('content.prepare', $files[$i]->description);
            }                

            if ($jlistConfig['auto.file.short.description'] && $jlistConfig['auto.file.short.description.value'] > 0){
                 if (strlen($files[$i]->description) > $jlistConfig['auto.file.short.description.value']){ 
                     $shorted_text=preg_replace("/[^ ]*$/", '..', substr($files[$i]->description, 0, $jlistConfig['auto.file.short.description.value']));
                     $html_file = str_replace('{description}', $shorted_text, $html_file);
                 } else {
                     $html_file = str_replace('{description}', $files[$i]->description, $html_file);
                 }    
            } else {
                 $html_file = str_replace('{description}', $files[$i]->description, $html_file);
            }   

            // compute for HOT symbol            
            if ($jlistConfig['loads.is.file.hot'] > 0 && $files[$i]->downloads >= $jlistConfig['loads.is.file.hot'] ){
                // is the old button pic used?
                if ($jlistConfig['use.css.buttons.instead.icons'] == '0'){
                    $html_file = str_replace('{pic_is_hot}', $hotpic, $html_file);
                } else {
                    // CSS Button is selected
                    $html_file = str_replace('{pic_is_hot}', '<span class="jdbutton '.$status_color_hot.' jstatus">'.JText::_('COM_JDOWNLOADS_HOT').'</span>', $html_file);
                }    
            } else {    
                $html_file = str_replace('{pic_is_hot}', '', $html_file);
            }

            // compute for NEW symbol
            $days_diff = JDHelper::computeDateDifference(date('Y-m-d H:i:s'), $files[$i]->date_added);
            if ($jlistConfig['days.is.file.new'] > 0 && $days_diff <= $jlistConfig['days.is.file.new']){
                // is the old button used?
                if ($jlistConfig['use.css.buttons.instead.icons'] == '0'){            
                    $html_file = str_replace('{pic_is_new}', $newpic, $html_file);
                } else {
                    // CSS Button is selected
                    $html_file = str_replace('{pic_is_new}', '<span class="jdbutton '.$status_color_new.' jstatus">'.JText::_('COM_JDOWNLOADS_NEW').'</span>', $html_file);
                }    
            } else {    
                $html_file = str_replace('{pic_is_new}', '', $html_file);
            }
            
            // compute for UPDATED symbol
            // view it only when in the download is activated the 'updated' option
            if ($files[$i]->update_active) {
                $days_diff = JDHelper::computeDateDifference(date('Y-m-d H:i:s'), $files[$i]->modified);
                if ($jlistConfig['days.is.file.updated'] > 0 && $days_diff >= 0 && $days_diff <= $jlistConfig['days.is.file.updated']){
                    if ($jlistConfig['use.css.buttons.instead.icons'] == '0'){
                        $html_file = str_replace('{pic_is_updated}', $updatepic, $html_file);
                    } else {
                        // CSS Button is selected
                        $html_file = str_replace('{pic_is_updated}', '<span class="jdbutton '.$status_color_updated.' jstatus">'.JText::_('COM_JDOWNLOADS_UPDATED').'</span>', $html_file);
                    }    
                } else {    
                    $html_file = str_replace('{pic_is_updated}', '', $html_file);
                }
            } else {
               $html_file = str_replace('{pic_is_updated}', '', $html_file);
            }    
            
            // media player
            if ($files[$i]->preview_filename){
                // we use the preview file when exist  
                $is_preview = true;
                $files[$i]->itemtype = JDHelper::getFileExtension($files[$i]->preview_filename);
                $is_playable    = JDHelper::isPlayable($files[$i]->preview_filename);
                $extern_media = false;
            } else {                  
                $is_preview = false;
                if ($files[$i]->extern_file){
                    $extern_media = true;
                    $files[$i]->itemtype = JDHelper::getFileExtension($files[$i]->extern_file);
                    $is_playable    = JDHelper::isPlayable($files[$i]->extern_file);
                } else {    
                    $files[$i]->itemtype = JDHelper::getFileExtension($files[$i]->url_download);
                    $is_playable    = JDHelper::isPlayable($files[$i]->url_download);
                    $extern_media = false;
                }  
            }            
            
            if ( !$jlistConfig['flowplayer.use'] && !$jlistConfig['html5player.use'] && $files[$i]->itemtype == 'mp3' ){
                // we use only the 'OLD' mp3 player
                if ($extern_media){
                    $mp3_path = $files[$i]->extern_file;
                } else {        
                    if ($is_preview){
                        // we need the path to the "previews" folder
                        $mp3_path = JUri::base().$jdownloads_root_dir_name.'/'.$jlistConfig['preview.files.folder.name'].'/'.$files[$i]->preview_filename;
                    } else {
                        // we use the normal download file for the player
                        $mp3_path = JUri::base().$jdownloads_root_dir_name.'/'.$category_dir.'/'.$files[$i]->url_download;
                    }   
                }    
                $mp3_config = trim($jlistConfig['mp3.player.config']);
                $mp3_config = str_replace(';', '&amp;', $mp3_config);
                
                $mp3_player =  
                '<object type="application/x-shockwave-flash" data="components/com_jdownloads/assets/mp3_player_maxi.swf" width="200" height="20">
                <param name="movie" value="components/com_jdownloads/assets/mp3_player_maxi.swf" />
                <param name="wmode" value="transparent"/>
                <param name="FlashVars" value="mp3='.$mp3_path.'&amp;'.$mp3_config.'" />
                </object>';   
                
                if (strpos($html_file, '{mp3_player}')){
                    $html_file = str_replace('{mp3_player}', $mp3_player, $html_file);
                    $html_file = str_replace('{preview_player}', '', $html_file);
                } else {                
                    $html_file = str_replace('{preview_player}', $mp3_player, $html_file);
                }
                
            } 
            
            if ( $is_playable ){
                
               if ($jlistConfig['html5player.use']){
                    // we will use the new HTML5 player option
                    if ($extern_media){
                        $media_path = $files[$i]->extern_file;
                    } else {        
                        if ($is_preview){
                            // we need the relative path to the "previews" folder
                            $media_path = $jdownloads_root_dir_name.'/'.$jlistConfig['preview.files.folder.name'].'/'.$files[$i]->preview_filename;
                        } else {
                            // we use the normal download file for the player
                            $media_path = $jdownloads_root_dir_name.'/'.$category_dir.'/'.$files[$i]->url_download;
                        }   
                    }    
                            
                    // create the HTML5 player
                    $player = JDHelper::getHTML5Player($files[$i], $media_path);
                    
                    // we use the player for video files only in listings, when the option allowed this
                    if ($jlistConfig['html5player.view.video.only.in.details'] && $files[$i]->itemtype != 'mp3' && $files[$i]->itemtype != 'wav' && $files[$i]->itemtype != 'oga'){
                        $html_file = str_replace('{mp3_player}', '', $html_file);
                        $html_file = str_replace('{preview_player}', '', $html_file);
                    } else {                            
                        if ($files[$i]->itemtype == 'mp4' || $files[$i]->itemtype == 'webm' || $files[$i]->itemtype == 'ogg' || $files[$i]->itemtype == 'ogv' || $files[$i]->itemtype == 'mp3' || $files[$i]->itemtype == 'wav' || $files[$i]->itemtype == 'oga'){
                            // We will replace at first the old placeholder when exist
                            if (strpos($html_file, '{mp3_player}')){
                                $html_file = str_replace('{mp3_player}', $player, $html_file);
                                $html_file = str_replace('{preview_player}', '', $html_file);
                            } else {                
                                $html_file = str_replace('{preview_player}', $player, $html_file);
                            }    
                        } else {
                            $html_file = str_replace('{mp3_player}', '', $html_file);
                            $html_file = str_replace('{preview_player}', '', $html_file);
                        }    
                    } 
                
            } else {
                
                    if ($jlistConfig['flowplayer.use']){
                        // we will use the new flowplayer option
                        if ($extern_media){
                            $media_path = $files[$i]->extern_file;
                        } else {        
                            if ($is_preview){
                                // we need the relative path to the "previews" folder
                                $media_path = $jdownloads_root_dir_name.'/'.$jlistConfig['preview.files.folder.name'].'/'.$files[$i]->preview_filename;
                            } else {
                                // we use the normal download file for the player
                                $media_path = $jdownloads_root_dir_name.'/'.$category_dir.'/'.$files[$i]->url_download;
                            }   
                        }    

                        $ipadcode = '';

                        if ($files[$i]->itemtype == 'mp3'){
                            $fullscreen = 'false';
                            $autohide = 'false';
                            $playerheight = (int)$jlistConfig['flowplayer.playerheight.audio'];
                            // we must use also the ipad plugin identifier when required
                            // see http://flowplayer.blacktrash.org/test/ipad-audio.html and http://flash.flowplayer.org/plugins/javascript/ipad.html
                            if ($this->ipad_user){
                               $ipadcode = '.ipad();'; 
                            }    
                        } else {
                            $fullscreen = 'true';
                            $autohide = 'true';
                            $playerheight = (int)$jlistConfig['flowplayer.playerheight'];
                        }
                        
                        $player = '<a href="'.$media_path.'" style="display:block;width:'.$jlistConfig['flowplayer.playerwidth'].'px;height:'.$playerheight.'px;" class="player" id="player'.$files[$i]->file_id.'"></a>';
                        $player .= '<script language="JavaScript">
                        // install flowplayer into container
                                    flowplayer("player'.$files[$i]->file_id.'", "'.JURI::base().'components/com_jdownloads/assets/flowplayer/flowplayer-3.2.16.swf",  
                                     {  
                            plugins: {
                                controls: {
                                    // insert at first the config settings
                                    '.$jlistConfig['flowplayer.control.settings'].'
                                    // and now the basics
                                    fullscreen: '.$fullscreen.',
                                    height: '.(int)$jlistConfig['flowplayer.playerheight.audio'].',
                                    autoHide: '.$autohide.',
                                }
                                
                            },
                            clip: {
                                autoPlay: false,
                                // optional: when playback starts close the first audio playback
                                 onBeforeBegin: function() {
                                    $f("player'.$files[$i]->file_id.'").close();
                                }
                            }
                        })'.$ipadcode.'; </script>';
                        // the 'ipad code' above is only required for ipad/iphone users                
                        
                        // we use the player for video files only in listings, when the option allowed this
                        if ($jlistConfig['flowplayer.view.video.only.in.details'] && $files[$i]->itemtype != 'mp3'){ 
                            $html_file = str_replace('{mp3_player}', '', $html_file);
                            $html_file = str_replace('{preview_player}', '', $html_file);            
                        } else {    
                            if ($files[$i]->itemtype == 'mp4' || $files[$i]->itemtype == 'flv' || $files[$i]->itemtype == 'mp3'){    
                                // We will replace at first the old placeholder when exist
                                if (strpos($html_file, '{mp3_player}')){
                                    $html_file = str_replace('{mp3_player}', $player, $html_file);
                                    $html_file = str_replace('{preview_player}', '', $html_file);
                                } else {
                                    $html_file = str_replace('{preview_player}', $player, $html_file);
                                }                                
                            } else {
                                $html_file = str_replace('{mp3_player}', '', $html_file);
                                $html_file = str_replace('{preview_player}', '', $html_file);
                            }
                        }
                    }
                }
            } 

                
            if ($jlistConfig['mp3.view.id3.info'] && $files[$i]->itemtype == 'mp3' && !$extern_media){
               // read mp3 infos
                if ($is_preview){
                    // get the path to the preview file
                    $mp3_path_abs = $jlistConfig['files.uploaddir'].DS.$jlistConfig['preview.files.folder.name'].DS.$files[$i]->preview_filename;
                } else {
                    // get the path to the downloads file
                    $mp3_path_abs = $jlistConfig['files.uploaddir'].DS.$category_dir.DS.$files[$i]->url_download;
                }
                $info = JDHelper::getID3v2Tags($mp3_path_abs);
                if ($info){
                    // add it
                    $mp3_info = '<div class="jd_mp3_id3tag_wrapper" style="max-width:'.(int)$jlistConfig['html5player.audio.width'].'px; ">'.stripslashes($jlistConfig['mp3.info.layout']).'</div>';
                    $mp3_info = str_replace('{name_title}', JText::_('COM_JDOWNLOADS_FE_VIEW_ID3_TITLE'), $mp3_info);
                    if ($is_preview){
                        $mp3_info = str_replace('{name}', $files[$i]->preview_filename, $mp3_info);
                    } else {
                        $mp3_info = str_replace('{name}', $files[$i]->url_download, $mp3_info);
                    } 
                    $mp3_info = str_replace('{album_title}', JText::_('COM_JDOWNLOADS_FE_VIEW_ID3_ALBUM'), $mp3_info);
                    $mp3_info = str_replace('{album}', $info['TALB'], $mp3_info);
                    $mp3_info = str_replace('{artist_title}', JText::_('COM_JDOWNLOADS_FE_VIEW_ID3_ARTIST'), $mp3_info);
                    $mp3_info = str_replace('{artist}', $info['TPE1'], $mp3_info);
                    $mp3_info = str_replace('{genre_title}', JText::_('COM_JDOWNLOADS_FE_VIEW_ID3_GENRE'), $mp3_info);
                    $mp3_info = str_replace('{genre}', $info['TCON'], $mp3_info);
                    $mp3_info = str_replace('{year_title}', JText::_('COM_JDOWNLOADS_FE_VIEW_ID3_YEAR'), $mp3_info);
                    $mp3_info = str_replace('{year}', $info['TYER'], $mp3_info);
                    $mp3_info = str_replace('{length_title}', JText::_('COM_JDOWNLOADS_FE_VIEW_ID3_LENGTH'), $mp3_info);
                    $mp3_info = str_replace('{length}', $info['TLEN'].' '.JText::_('COM_JDOWNLOADS_FE_VIEW_ID3_MINS'), $mp3_info);                    
                    $mp3_info = '<div class="jd_mp3_id3tag_wrapper" style="max-width:'.(int)$jlistConfig['html5player.audio.width'].'px; ">'.$mp3_info.'</div>';
                    $html_file = str_replace('{mp3_id3_tag}', $mp3_info, $html_file); 
                }     
            }        
            $html_file = str_replace('{mp3_player}', '', $html_file);
            $html_file = str_replace('{mp3_id3_tag}', '', $html_file);
            $html_file = str_replace('{preview_player}', '', $html_file);             

            // replace the {preview_url}
            if ($files[$i]->preview_filename){
                // we need the relative path to the "previews" folder
                $media_path = $jdownloads_root_dir_name.'/'.$jlistConfig['preview.files.folder.name'].'/'.$files[$i]->preview_filename;
                $html_file = str_replace('{preview_url}', $media_path, $html_file);
            } else {
                $html_file = str_replace('{preview_url}', '', $html_file);
            }   
            
            // build the license info data and build link
            if ($files[$i]->license == '') $files[$i]->license = 0;
            $lic_data = '';

            if ($files[$i]->license_url != '') {
                 $lic_data = $pic_license.'<a href="'.$files[$i]->license_url.'" target="_blank" rel="nofollow" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_LICENCE').'">'.$files[$i]->license_title.'</a> '.$extern_url_pic;
            } else {
                if ($files[$i]->license_title != '') {
                     if ($files[$i]->license_text != '') {
                          $lic_data = $pic_license.$files[$i]->license_title;
                          $lic_data .= JHtml::_('tooltip', $files[$i]->license_text, $files[$i]->license_title);
                     } else {
                          $lic_data = $pic_license.$files[$i]->license_title;
                     }
                } else {
                    $lic_data = '';
                }
            }
            $html_file = str_replace('{license_text}', $lic_data, $html_file);
            
            // display checkboxes only, when the user have the correct access permissions and it is activated in layout ( = 0 !! )
            if ( $layout_files->checkbox_off == 0 ) {
                 $html_file = str_replace('{checkbox_list}',$checkbox_list, $html_file);
            } else {
                 $html_file = str_replace('{checkbox_list}','', $html_file);
            }

            $html_file = str_replace('{cat_id}', $files[$i]->cat_id, $html_file);
            
            // file size
            if ($files[$i]->size == '' || $files[$i]->size == '0 B') {
                $html_file = str_replace('{size}', '', $html_file);
                $html_file = str_replace('{filesize_value}', '', $html_file);
            } else {
                $html_file = str_replace('{size}', $pic_size.$files[$i]->size, $html_file);
                $html_file = str_replace('{filesize_value}', $pic_size.$files[$i]->size, $html_file);
            }
            
            // price
            if ($files[$i]->price != '') {
                $html_file = str_replace('{price_value}', $pic_price.$files[$i]->price, $html_file);
            } else {
                $html_file = str_replace('{price_value}', '', $html_file);
            }

            // file_date
            if ($files[$i]->file_date != '0000-00-00 00:00:00') {
                 if ($this->params->get('show_date') == 0){ 
                     $filedate_data = $pic_date.JHtml::_('date',$files[$i]->file_date, $date_format['long']);
                 } else {
                     $filedate_data = $pic_date.JHtml::_('date',$files[$i]->file_date, $date_format['short']);
                 }    
            } else {
                 $filedate_data = '';
            }
            $html_file = str_replace('{file_date}',$filedate_data, $html_file);
            
            // date_added
            if ($files[$i]->date_added != '0000-00-00 00:00:00') {
                if ($this->params->get('show_date') == 0){ 
                    // use 'normal' date-time format field
                    $date_data = $pic_date.JHtml::_('date',$files[$i]->date_added, $date_format['long']);
                } else {
                    // use 'short' date-time format field
                    $date_data = $pic_date.JHtml::_('date',$files[$i]->date_added, $date_format['short']);
                }    
            } else {
                 $date_data = '';
            }
            $html_file = str_replace('{date_added}',$date_data, $html_file);
            $html_file = str_replace('{created_date_value}',$date_data, $html_file);
            
            if ($files[$i]->creator){
                $html_file = str_replace('{created_by_value}', $files[$i]->creator, $html_file);
            } else {
                $html_file = str_replace('{created_by_value}', '', $html_file);
            }                
            if ($files[$i]->modifier){
                $html_file = str_replace('{modified_by_value}', $files[$i]->modifier, $html_file);
            } else {                              
                $html_file = str_replace('{modified_by_value}', '', $html_file);
            }
            
            // modified_date
            if ($files[$i]->modified_date != '0000-00-00 00:00:00') {
                if ($this->params->get('show_date') == 0){ 
                    $modified_data = $pic_date.JHtml::_('date',$files[$i]->modified_date, $date_format['long']);
                } else {
                    $modified_data = $pic_date.JHtml::_('date',$files[$i]->modified_date, $date_format['short']);
                }    
            } else {
                $modified_data = '';
            }
            $html_file = str_replace('{modified_date_value}',$modified_data, $html_file);

            $user_can_see_download_url = 0;
            
            // only view download-url when user has correct access level
            if ($files[$i]->params->get('access-download') == true){ 
                $user_can_see_download_url++;
                $blank_window = '';
                $blank_window1 = '';
                $blank_window2 = '';
                // get file extension
                $view_types = array();
                $view_types = explode(',', $jlistConfig['file.types.view']);
                $only_file_name = basename($files[$i]->url_download);
                $fileextension = JDHelper::getFileExtension($only_file_name);
                if (in_array($fileextension, $view_types)){
                    $blank_window = 'target="_blank"';
                }    
                // check is set link to a new window?
                if ($files[$i]->extern_file && $files[$i]->extern_site   ){
                    $blank_window = 'target="_blank"';
                }

                // direct download without summary page?
                if ($jlistConfig['direct.download'] == '0'){
                     $url_task = 'summary';
                     $download_link = JRoute::_(JDownloadsHelperRoute::getOtherRoute($files[$i]->slug, $files[$i]->cat_id, $files[$i]->language, $url_task));
                } else {
                     if ($files[$i]->license_agree || $files[$i]->password || $jd_user_settings->view_captcha) {
                         // user must agree the license - fill out a password field - or fill out the captcha human check - so we must view the summary page!
                         $url_task = 'summary';
                         $download_link = JRoute::_(JDownloadsHelperRoute::getOtherRoute($files[$i]->slug, $files[$i]->cat_id, $files[$i]->language, $url_task));
                     } else {     
                         $url_task = 'download.send';
                         $download_link = JRoute::_('index.php?option=com_jdownloads&amp;task=download.send&amp;id='.$files[$i]->file_id.'&amp;catid='.$files[$i]->cat_id.'&amp;m=0');
                     }    
                }
                
                // when we have not a menu item to the singel download, we need a menu item from the assigned category, or at lates the root itemid
                if ($files[$i]->menuf_itemid){
                     $file_itemid =  (int)$files[$i]->menuf_itemid;
                } else {
                     $file_itemid = $this->category->menu_itemid;
                }                      
                 
                if ($url_task == 'download.send'){ 
                    // is the old button used?
                    if ($jlistConfig['use.css.buttons.instead.icons'] == '0'){ 
                          $download_link_text = '<a '.$blank_window.' href="'.$download_link.'" title="'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'" class="jd_download_url">';
                    } else {
                          $download_link_text = '<a '.$blank_window.' href="'.$download_link.'" title="'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'" class="jdbutton '.$download_color.' '.$download_size_listings.'">';
                    }                           
                } else {
                    // is the old button used?
                    if ($jlistConfig['use.css.buttons.instead.icons'] == '0'){ 
                        $download_link_text = '<a href="'.$download_link.'" title="'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'">';                  
                    } else {
                        $download_link_text = '<a href="'.$download_link.'" title="'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'" class="jdbutton '.$download_color.' '.$download_size_listings.'">';                  
                    }                         
                }
                    
                if ($jlistConfig['use.css.buttons.instead.icons'] == '0'){
                     $pic_download = '<img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.files'].'" style="text-align:middle;border:0px;" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" />';
                } else {
                    $pic_download = '';
                }    
                    
                // view not any download link, when we have not really a file
                if ($has_no_file || !$files[$i]->state){
                    // remove download placeholder
                    $html_file = str_replace('{url_download}', '', $html_file); 
                } else {
                    // insert here the complete download link 
                    if ($jlistConfig['view.also.download.link.text'] && $jlistConfig['use.css.buttons.instead.icons'] == '0'){
                        $html_file = str_replace('{url_download}',$download_link_text.$pic_download.'<br />'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'</a>', $html_file);
                    } elseif ($jlistConfig['use.css.buttons.instead.icons'] == '1') {
                        $html_file = str_replace('{url_download}',$download_link_text.$pic_download.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'</a>', $html_file);  
                    } else {
                        $html_file = str_replace('{url_download}',$download_link_text.$pic_download.'</a>', $html_file);  
                    }
                } 
                                                                        
                // view mirrors - but only when is published
                if ($files[$i]->mirror_1 && $files[$i]->state) {
                    if ($files[$i]->extern_site_mirror_1 && $url_task == 'download.send'){
                        $blank_window1 = 'target="_blank"';
                    }
                    $mirror1_link_dum = JRoute::_('index.php?option=com_jdownloads&amp;task=download.send&amp;id='.$files[$i]->file_id.'&amp;catid='.$files[$i]->cat_id.'&amp;m=1');
                    //$mirror1_link_dum = JRoute::_(JDownloadsHelperRoute::getOtherRoute($files[$i]->slug, $files[$i]->cat_id, $files[$i]->language, $url_task, 1));
                    // is the old button used?
                    if ($jlistConfig['use.css.buttons.instead.icons'] == '0'){                
                        $mirror1_link = '<a '.$blank_window1.' href="'.$mirror1_link_dum.'" class="jd_download_url"><img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.mirror_1'].'" style="border:0px;" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_1').'" /></a>';
                    } else {
                        // we use the new css button 
                        $mirror1_link = '<a '.$blank_window1.' href="'.$mirror1_link_dum.'" alt="'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'" class="jdbutton '.$download_color_mirror1.' '.$download_size_mirror.'">'.JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_1').'</a>'; 
                    }                     
                    $html_file = str_replace('{mirror_1}', $mirror1_link, $html_file);
                } else {
                    $html_file = str_replace('{mirror_1}', '', $html_file);
                }                    

                if ($files[$i]->mirror_2 && $files[$i]->state) {
                    if ($files[$i]->extern_site_mirror_2 && $url_task == 'download.send'){
                        $blank_window2 = 'target="_blank"';
                    }
                    $mirror2_link_dum = JRoute::_('index.php?option=com_jdownloads&amp;task=download.send&amp;id='.$files[$i]->file_id.'&amp;catid='.$files[$i]->cat_id.'&amp;m=2');
                    //$mirror2_link_dum = JRoute::_(JDownloadsHelperRoute::getOtherRoute($files[$i]->slug, $files[$i]->cat_id, $files[$i]->language, $url_task, 2));
                    // is the old button used?
                    if ($jlistConfig['use.css.buttons.instead.icons'] == '0'){                
                        $mirror2_link = '<a '.$blank_window2.' href="'.$mirror2_link_dum.'" class="jd_download_url"><img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.mirror_2'].'" style="border:0px;" alt="'.JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_2').'" /></a>';
                    } else {
                        // we use the new css button 
                        $mirror2_link = '<a '.$blank_window2.' href="'.$mirror2_link_dum.'" alt="'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'" class="jdbutton '.$download_color_mirror2.' '.$download_size_mirror.'">'.JText::_('COM_JDOWNLOADS_FRONTEND_MIRROR_URL_TITLE_2').'</a>'; 
                    }                     
                    $html_file = str_replace('{mirror_2}', $mirror2_link, $html_file);
                } else {
                    $html_file = str_replace('{mirror_2}', '', $html_file);
                }            
            } else {
                $html_file = str_replace('{url_download}', '', $html_file);
                $html_file = str_replace('{mirror_1}', '', $html_file); 
                $html_file = str_replace('{mirror_2}', '', $html_file); 
            }
            
                $title_link = JRoute::_(JDownloadsHelperRoute::getDownloadRoute($files[$i]->slug, $files[$i]->cat_id, $files[$i]->language));
                $titel_link_text = '<a href="'.$title_link.'">'.$this->escape($files[$i]->file_title).'</a>';
                $detail_link_text = '<a href="'.$title_link.'">'.JText::_('COM_JDOWNLOADS_FE_DETAILS_LINK_TEXT_TO_DETAILS').'</a>';
            
            if ($jlistConfig['view.detailsite']){
                // Symbol anzeigen - auch als url
                if ($files[$i]->file_pic != '' ) {
                    $filepic = '<a href="'.$title_link.'">'.'<img src="'.JURI::base().'images/jdownloads/fileimages/'.$files[$i]->file_pic.'" style="text-align:top;border:0px;" width="'.$jlistConfig['file.pic.size'].'" height="'.$jlistConfig['file.pic.size.height'].'" alt="'.$files[$i]->file_pic.'" /></a> ';
                } else {
                    $filepic = '';
                }
                $html_file = str_replace('{file_pic}',$filepic, $html_file);
                $html_file = str_replace('{file_title}', $titel_link_text.' '.$editIcon, $html_file);
                
            } elseif ($jlistConfig['use.download.title.as.download.link']){
                
                if ($user_can_see_download_url && !$has_no_file){
                    // build title link as download link
                   if ($url_task == 'download.send'){ 
                      $download_link_text = '<a '.$blank_window.' href="'.$download_link.'" title="'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'" class="jd_download_url">'.$files[$i]->file_title.'</a>';
                   } else {
                      $download_link_text = '<a href="'.$download_link.'" title="'.JText::_('COM_JDOWNLOADS_LINKTEXT_DOWNLOAD_URL').'">'.$files[$i]->file_title.'</a>';                  
                   }
                   // View file icon also with link
                   if ($files[$i]->file_pic != '' ) {
                        $filepic = '<a href="'.$download_link.'"><img src="'.JURI::base().'images/jdownloads/fileimages/'.$files[$i]->file_pic.'" style="text-align:top;border:0px;" width="'.$jlistConfig['file.pic.size'].'" height="'.$jlistConfig['file.pic.size.height'].'" alt="'.$files[$i]->file_pic.'" /></a>';
                   } else {
                        $filepic = '';
                   }
                   $html_file = str_replace('{file_pic}',$filepic, $html_file);
                   $html_file = str_replace('{file_title}', $download_link_text.' '.$editIcon, $html_file);
                } else {
                    // user may not use download link
                    $html_file = str_replace('{file_title}', $files[$i]->file_title, $html_file);
                    if ($files[$i]->file_pic != '' ) {
                        $filepic = '<img src="'.JURI::base().'images/jdownloads/fileimages/'.$files[$i]->file_pic.'" style="text-align:top;border:0px;" width="'.$jlistConfig['file.pic.size'].'" height="'.$jlistConfig['file.pic.size.height'].'" alt="'.$files[$i]->file_pic.'" />';
                    } else {
                        $filepic = '';
                    }
                    $html_file = str_replace('{file_pic}',$filepic, $html_file);
                }    
            } else {
                // no links
                if ($files[$i]->file_pic != '' ) {
                    $filepic = '<img src="'.JURI::base().'images/jdownloads/fileimages/'.$files[$i]->file_pic.'" style="text-align:top;border:0px;" width="'.$jlistConfig['file.pic.size'].'" height="'.$jlistConfig['file.pic.size.height'].'" alt="'.$files[$i]->file_pic.'" />';
                } else {
                    $filepic = '';
                }
                $html_file = str_replace('{file_pic}',$filepic, $html_file);
                // remove link to details view at the end
                $html_file = str_replace('{file_title}', $files[$i]->file_title.' '.$editIcon, $html_file);
            }             
            
            // The link to detail view is always displayed - when not required must be removed the placeholder from the layout
            $html_file = str_replace('{link_to_details}', $detail_link_text, $html_file);
            
            
            // build website url
            if (!$files[$i]->url_home == '') {
                 if (strpos($files[$i]->url_home, 'http://') !== false) {    
                     $html_file = str_replace('{url_home}',$pic_website.'<a href="'.$files[$i]->url_home.'" target="_blank" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'">'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
                     $html_file = str_replace('{author_url_text} ',$pic_website.'<a href="'.$files[$i]->url_home.'" target="_blank" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'">'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
                 } else {
                     $html_file = str_replace('{url_home}',$pic_website.'<a href="http://'.$files[$i]->url_home.'" target="_blank" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'">'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
                     $html_file = str_replace('{author_url_text}',$pic_website.'<a href="http://'.$files[$i]->url_home.'" target="_blank" title="'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'">'.JText::_('COM_JDOWNLOADS_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
                 }    
            } else {
                $html_file = str_replace('{url_home}', '', $html_file);
                $html_file = str_replace('{author_url_text}', '', $html_file);
            }

            // encode is link a mail
            if (strpos($files[$i]->url_author, '@') && $jlistConfig['mail.cloaking']){
                if (!$files[$i]->author) { 
                    $mail_encode = JHtml::_('email.cloak', $files[$i]->url_author);
                } else {
                    $mail_encode = JHtml::_('email.cloak',$files[$i]->url_author, true, $files[$i]->author, false);
                }        
            }
                    
            // build author link
            if ($files[$i]->author <> ''){
                if ($files[$i]->url_author <> '') {
                    if ($mail_encode) {
                        $link_author = $pic_author.$mail_encode;
                    } else {
                        if (strpos($files[$i]->url_author, 'http://') !== false) {    
                            $link_author = $pic_author.'<a href="'.$files[$i]->url_author.'" target="_blank">'.$files[$i]->author.'</a> '.$extern_url_pic;
                        } else {
                            $link_author = $pic_author.'<a href="http://'.$files[$i]->url_author.'" target="_blank">'.$files[$i]->author.'</a> '.$extern_url_pic;
                        }        
                    }
                    $html_file = str_replace('{author}',$link_author, $html_file);
                    $html_file = str_replace('{author_text}',$link_author, $html_file);
                    $html_file = str_replace('{url_author}', '', $html_file);
                } else {
                    $link_author = $pic_author.$files[$i]->author;
                    $html_file = str_replace('{author}',$link_author, $html_file);
                    $html_file = str_replace('{author_text}',$link_author, $html_file);
                    $html_file = str_replace('{url_author}', '', $html_file);
                }
            } else {
                    $html_file = str_replace('{url_author}', $pic_author.$files[$i]->url_author, $html_file);
                    $html_file = str_replace('{author}','', $html_file);
                    $html_file = str_replace('{author_text}','', $html_file); 
            }

            // set system value
            $file_sys_values = explode(',' , JDHelper::getOnlyLanguageSubstring($jlistConfig['system.list']));
            if ($files[$i]->system == 0 ) {
                $html_file = str_replace('{system}', '', $html_file);
                 $html_file = str_replace('{system_text}', '', $html_file); 
            } else {
                $html_file = str_replace('{system}', $pic_system.$file_sys_values[$files[$i]->system], $html_file);
                $html_file = str_replace('{system_text}', $pic_system.$file_sys_values[$files[$i]->system], $html_file);
            }

            // set file language value
            $file_lang_values = explode(',' ,JDHelper::getOnlyLanguageSubstring($jlistConfig['language.list']));
            if ($files[$i]->file_language == 0 ) {
                $html_file = str_replace('{language}', '', $html_file);
                $html_file = str_replace('{language_text}', '', $html_file);
            } else {
                $html_file = str_replace('{language}', $pic_language.$file_lang_values[$files[$i]->file_language], $html_file);
                $html_file = str_replace('{language_text}', $pic_language.$file_lang_values[$files[$i]->file_language], $html_file);
            }

            // insert rating system
            if ($jlistConfig['view.ratings']){
                $rating_system = JDHelper::getRatings($files[$i]->file_id, $files[$i]->rating_count, $files[$i]->rating_sum);
                $html_file = str_replace('{rating}', $rating_system, $html_file);
                $html_file = str_replace('{rating_title}', JText::_('COM_JDOWNLOADS_RATING_LABEL'), $html_file);
            } else {
                $html_file = str_replace('{rating}', '', $html_file);
                $html_file = str_replace('{rating_title}', '', $html_file);
            }
            
            // custom fields
            $custom_fields_arr = JDHelper::existsCustomFieldsTitles();
            $row_custom_values = array('dummy',$files[$i]->custom_field_1, $files[$i]->custom_field_2, $files[$i]->custom_field_3, $files[$i]->custom_field_4, $files[$i]->custom_field_5,
                               $files[$i]->custom_field_6, $files[$i]->custom_field_7, $files[$i]->custom_field_8, $files[$i]->custom_field_9, $files[$i]->custom_field_10, $files[$i]->custom_field_11, $files[$i]->custom_field_12, $files[$i]->custom_field_13, $files[$i]->custom_field_14);
            for ($x=1; $x<15; $x++){
                // replace placeholder with title and value
                if (in_array($x,$custom_fields_arr[0]) && $row_custom_values[$x] && $row_custom_values[$x] != '0000-00-00'){
					// add in label field
                    $html_file = str_replace("{custom_title_$x}", $custom_fields_arr[1][$x-1], $html_file);
					// see if checkbox type
					if ($x <= 5) {
					    $html_file = str_replace("{custom_value_$x}", $custom_fields_arr[2][$x-1][$row_custom_values[$x]], $html_file);
					}
					//see if short or long text box
                    if (($x > 5 && $x < 11)||($x >12)){
                        $html_file = str_replace("{custom_value_$x}", stripslashes($row_custom_values[$x]), $html_file);
                    }
			        //  handle custom dates
					if ($x == 11 || $x == 12) {
						if ($this->params->get('show_date') == 0){ 
								$custom_date = JHtml::_('date',stripslashes($row_custom_values[$x]), $date_format['long']);
						} else {
								$custom_date = JHtml::_('date',stripslashes($row_custom_values[$x]), $date_format['short']);
						}    
						$html_file = str_replace("{custom_value_$x}",$custom_date, $html_file);
					}    
                } else {
                    // remove placeholder
                    if ($jlistConfig['remove.field.title.when.empty']){
                        $html_file = str_replace("{custom_title_$x}", '', $html_file);
                    } else {
                        $html_file = str_replace("{custom_title_$x}", $custom_fields_arr[1][$x-1], $html_file);
                    }    
                    $html_file = str_replace("{custom_value_$x}", '', $html_file);
                }    
            }
            
            $html_file = str_replace('{downloads}',$pic_downloads.JDHelper::strToNumber((int)$files[$i]->downloads), $html_file);
            $html_file = str_replace('{hits_value}',$pic_downloads.JDHelper::strToNumber((int)$files[$i]->downloads), $html_file);
            $html_file = str_replace('{ordering}',$files[$i]->ordering, $html_file);
            $html_file = str_replace('{published}',$files[$i]->published, $html_file);
            
            // support for content plugins 
            if ($jlistConfig['activate.general.plugin.support'] && !$jlistConfig['use.general.plugin.support.only.for.descriptions']) {  
                $html_file = JHtml::_('content.prepare', $html_file);

            }

            $html_files .= $html_file;
            $html_files .= $files[$i]->event->afterDisplayContent;
        }

        // add template_before_text and template_after_text
        $html_files = $layout_files->template_before_text.$html_files.$layout_files->template_after_text;
        
        // display only downloads area when it exist data here
        if ($total_downloads > 0){
            $body_cat = str_replace('{files}', $html_files, $body_cat);
        } else {
            $no_files_msg = '';
            if ($jlistConfig['view.no.file.message.in.empty.category']){
                $no_files_msg = '<br />'.JText::_('COM_JDOWNLOADS_FRONTEND_NOFILES').'<br /><br />';            
            } 
            $body_cat = str_replace('{files}', $no_files_msg, $body_cat);
        }    

        $checkbox_top_is_form = false;

        // display top checkbox only when the user can download any files here - right access permissions
        if ($user_can_see_download_url && $amount_downloads_with_file){ 
            $checkbox_top = '<form name="down'.$formid.'" action="'.JRoute::_('index.php?option=com_jdownloads&amp;view=summary&amp;Itemid='.$file_itemid).'"
                    onsubmit="return pruefen('.$formid.',\''.JText::_('COM_JDOWNLOADS_JAVASCRIPT_TEXT_1').'\');" method="post">
                    '.JDHelper::getOnlyLanguageSubstring($jlistConfig['checkbox.top.text']).'<input type="checkbox" name="toggle"
                    value="" onclick="checkAlle('.$i.','.$formid.');">';
            
            // view top checkbox only when activated in layout
            if ($layout_files->checkbox_off == 0 && !empty($files)) {
               $body_cat = str_replace('{checkbox_top}', $checkbox_top, $body_cat);
               $checkbox_top_is_form = true;
            } else {
               $body_cat = str_replace('{checkbox_top}', '', $body_cat);
            }   
        } else {
            // view message for missing access permissions
            if (!$user_can_see_download_url){
                if ($user->guest){
                    $regg = str_replace('<br />', '', JText::_('COM_JDOWNLOADS_FRONTEND_CAT_ACCESS_REGGED'));
                } else {
                    $regg = str_replace('<br />', '', JText::_('COM_JDOWNLOADS_FRONTEND_FILE_ACCESS_REGGED2'));
                }               
            
                if ($total_downloads > 0){
                    $body_cat = str_replace('{checkbox_top}', '<div class="jd_no_permission_category" style="text-align:center;"><img src="'.JURI::base().'components/com_jdownloads/assets/images/info32.png" style="text-align:middle;border:0px;" width="32" height="32" alt="info" /> '.$regg.'</div>', $body_cat);                    
                } else {
                    $body_cat = str_replace('{checkbox_top}', '', $body_cat);                    
                }    
            } else {
                $body_cat = str_replace('{checkbox_top}', '', $body_cat);                    
            }
        }
                
        $form_hidden = '<input type="hidden" name="boxchecked" value=""/> ';
        $body_cat = str_replace('{form_hidden}', $form_hidden, $body_cat);
        $body_cat .= '<input type="hidden" name="catid" value="'.$catid.'"/>';
        $body_cat .= JHtml::_( 'form.token' );
        if ($checkbox_top_is_form){
            $body_cat .= '</form>';
        }

        // view submit button only when checkboxes are activated
        $button = '<input class="button" type="submit" name="weiter" value="'.JText::_('COM_JDOWNLOADS_FORM_BUTTON_TEXT').'"/>';
        
        // view only submit button when user has correct access level and checkboxes are used in layout
        if ($layout_files->checkbox_off == 0 && !empty($files) && ($user_can_see_download_url && $amount_downloads_with_file)) {
            $body_cat = str_replace('{form_button}', $button, $body_cat);
        } else {
            $body_cat = str_replace('{form_button}', '', $body_cat);
        }        
        
        $html .= $body_cat;   
        
    }    
        
  
    // ==========================================
    // FOOTER SECTION  
    // ==========================================

    // display pagination            
    if ($jlistConfig['option.navigate.bottom'] && $this->pagination->get('pages.total') > 1 && $this->params->get('show_pagination') != '0' 
        || (!$jlistConfig['option.navigate.bottom'] && $this->pagination->get('pages.total') > 1 && $this->params->get('show_pagination') == '1') )
    {
        $page_navi_links = $this->pagination->getPagesLinks(); 
        if ($page_navi_links){
            $page_navi_pages   = $this->pagination->getPagesCounter();
            $page_navi_counter = $this->pagination->getResultsCounter(); 
            $page_limit_box    = $this->pagination->getLimitBox();  
        }    
        $footer = str_replace('{page_navigation}', $page_navi_links, $footer);
        $footer = str_replace('{page_navigation_results_counter}', $page_navi_counter, $footer);
        
        if ($this->params->get('show_pagination_results') == null || $this->params->get('show_pagination_results') == '1'){
            $footer = str_replace('{page_navigation_pages_counter}', $page_navi_pages, $footer); 
        } else {
            $footer = str_replace('{page_navigation_pages_counter}', '', $footer);                
        }             
    } else {
        $footer = str_replace('{page_navigation}', '', $footer);
        $footer = str_replace('{page_navigation_results_counter}', '', $footer);
        $footer = str_replace('{page_navigation_pages_counter}', '', $footer);                
    }

    // components footer text
    if ($jlistConfig['downloads.footer.text'] != '') {
        $footer_text = stripslashes(JDHelper::getOnlyLanguageSubstring($jlistConfig['downloads.footer.text']));
        if ($jlistConfig['google.adsense.active'] && $jlistConfig['google.adsense.code'] != ''){
            $footer_text = str_replace( '{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $footer_text);
        } else {
            $footer_text = str_replace( '{google_adsense}', '', $footer_text);
        }   
        $html .= $footer_text;
    }
    
    // back button
    if ($jlistConfig['view.back.button']){
        $footer = str_replace('{back_link}', '<a href="javascript:history.go(-1)">'.JText::_('COM_JDOWNLOADS_FRONTEND_BACK_BUTTON').'</a>', $footer); 
    } else {
        $footer = str_replace('{back_link}', '', $footer);
    }    
    
    $footer .= JDHelper::checkCom();
   
    $html .= $footer; 
    
    $html .= '</div>';
    
    // support for content plugins
    if ($jlistConfig['activate.general.plugin.support'] && !$jlistConfig['use.general.plugin.support.only.for.descriptions']) {
        $html = JHtml::_('content.prepare', $html);
    }

    // remove empty html tags
    if ($jlistConfig['remove.empty.tags']){
        $html = JDHelper::removeEmptyTags($html);
    }
    
    // ==========================================
    // VIEW THE BUILDED OUTPUT
    // ==========================================

    if ( !$jlistConfig['offline'] ) {
            echo $html;
    } else {
        // admins can view it always
        if ($is_admin) {
            echo $html;     
        } else {
            // build the offline message
            $html = '';
            // offline message
            if ($jlistConfig['offline.text'] != '') {
                $html .= JDHelper::getOnlyLanguageSubstring($jlistConfig['offline.text']);
            }
            echo $html;   
        }
    }     
 
?>