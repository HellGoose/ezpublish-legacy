<?php
//
// Created on: <19-Sep-2002 15:40:08 kk>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/classes/ezrssexport.php' );
include_once( 'kernel/classes/ezrssexportitem.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

function storeRSSExport( &$Module, &$http, $publish = false )
{
    for ( $itemCount = 0; $itemCount < $http->postVariable( 'Item_Count' ); $itemCount++)
    {
        $rssExportItem =& eZRSSExportItem::fetch( $http->postVariable( 'Item_ID_'.$itemCount ) );
//        $rssExportItem->setAttribute( 'source_node_id', $http->postVariable( 'Item_Source_'.$itemCount ) ); // This is set by browse
        $rssExportItem->setAttribute( 'class_id', $http->postVariable( 'Item_Class_'.$itemCount ) );
//        $rssExportItem->setAttribute( 'url_id', $http->postVariable( '' )); //This is not used, generated by node object id and site access
        $rssExportItem->setAttribute( 'title', $http->postVariable( 'Item_Class_Attribute_Title_'.$itemCount ) );
        $rssExportItem->setAttribute( 'description', $http->postVariable( 'Item_Class_Attribute_Description_'.$itemCount ) );
        $rssExportItem->store();
    }
    $rssExport =& eZRSSExport::fetch( $http->postVariable( 'RSSExport_ID' ) );
    $rssExport->setAttribute( 'title', $http->postVariable( 'title' ) );
    $rssExport->setAttribute( 'url', $http->postVariable( 'url' ) );
    $rssExport->setAttribute( 'site_access', $http->postVariable( 'SiteAccess' ) );
    $rssExport->setAttribute( 'description', $http->postVariable( 'Description' ) );
    if ( $http->hasPostVariable( 'active' ) )
        $rssExport->setAttribute( 'active', 1 );
    else
        $rssExport->setAttribute( 'active', 0 );
    $rssExport->setAttribute( 'access_url', $http->postVariable( 'Access_URL' ) );

    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();
    $rssExport->setAttribute( 'modifier_id', $user->attribute( "contentobject_id" ) );

    if ( $publish !== false )
    {
        $rssExport->setAttribute( 'status', '1' );
    }
    $rssExport->store();
    if ( $publish !== false )
        return $Module->run( 'list', array() );
}

?>
