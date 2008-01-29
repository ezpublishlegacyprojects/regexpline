<?php

/*
    Regular Expression datatype for eZ publish 3.x
    Copyright (C) 2005-2006  Hans Melis

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

/*!
  \class   hmregexplinetype hmregexplinetype.php
  \ingroup eZDatatype
  \brief   Handles the datatype regexpline
  \version 2.0
  \date    Wednesday 01 March 2006 17:19:23 pm
  \author  Hans Melis

  By using regexpline you can ... do stuff :)

*/

include_once( 'kernel/classes/ezdatatype.php' );

include_once( 'lib/ezutils/classes/ezini.php' );

include_once( 'kernel/common/i18n.php' );

define( 'EZ_DATATYPESTRING_REGEXPLINE', 'hmregexpline' );

class hmregexplinetype extends eZDataType
{
    /*!
      Constructor
    */
    function hmregexplinetype()
    {
        $this->eZDataType( EZ_DATATYPESTRING_REGEXPLINE,
                           ezi18n( 'extension/regexpline/datatype', 'Regular Expression Text', 'Datatype name' ),
                           array( 'serialize_supported' => true ) );

        $this->KeepTags = null;
    }

    /*!
    Validates all variables given on content class level
     \return EZ_INPUT_VALIDATOR_STATE_ACCEPTED or EZ_INPUT_VALIDATOR_STATE_INVALID if
             the values are accepted or not
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $regexpName = $base . "_hmregexpline_regexp_" . $classAttribute->attribute( 'id' );
        $presetName = $base . "_hmregexpline_preset_" . $classAttribute->attribute( 'id' );

        $regexp = $preset = array();
        
        $this->clearClassErrorMessages( $classAttribute );

        if( $http->hasPostVariable( $regexpName ) )
        {
            $regexp = $http->postVariable( $regexpName );
        }

        if( $http->hasPostVariable( $presetName ) )
        {
            $preset = $http->postVariable( $presetName );
        }

        $content = array( 'regexp' => $regexp,
                          'preset' => $preset );
        $regexp = $this->getRegularExpression( $content );
        
        if( count( $regexp ) == 0 )
        {
            $this->addClassErrorMessage( $classAttribute,
                ezi18n( 'extension/regexpline/datatype', 'You need at least one regular expression or selected preset' ) );        
        	return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }

        foreach( $regexp as $expr )
        {
            $check = @preg_match( $expr, 'Dummy string' );

            if( $check === false )
            {
            	$this->addClassErrorMessage( $classAttribute,
                    ezi18n( 'extension/regexpline/datatype', 'The regular expression "%1" is invalid', null, array( $expr ) ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }

        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches all variables inputed on content class level
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $regexpName = $base . "_hmregexpline_regexp_" . $classAttribute->attribute( 'id' );
        $errorsName = $base . "_hmregexpline_errmsg_" . $classAttribute->attribute( 'id' );
        $helpName = $base . "_hmregexpline_helptext_" . $classAttribute->attribute( 'id' );
        $patternName = $base . "_hmregexpline_namepattern_" . $classAttribute->attribute( 'id' );
        $presetName = $base . "_hmregexpline_preset_" . $classAttribute->attribute( 'id' );
        $displayName = $base . "_hmregexpline_display_"  . $classAttribute->attribute( 'id' );
        $negateName = $base . "_hmregexpline_negate_" . $classAttribute->attribute( 'id' );

        $content = $classAttribute->content();

        if( $http->hasPostVariable( $regexpName ) )
        {
            $content['regexp'] = $http->postVariable( $regexpName );
        }

        if( $http->hasPostVariable( $errorsName ) )
        {
            $content['error_messages'] = $http->postVariable( $errorsName );
        }

        if( $http->hasPostVariable( $presetName ) )
        {
            $content['preset'] = $http->postVariable( $presetName );
        }
        else if( $http->hasPostVariable( 'ContentClassHasInput' ) )
        {
            $content['preset'] = array();
        }

        if( $http->hasPostVariable( $helpName ) )
        {
            $content['help_text'] = $http->postVariable( $helpName );
        }

        if( $http->hasPostVariable( $patternName ) )
        {
            $content['naming_pattern'] = $http->postVariable( $patternName );
        }
        else if( $http->hasPostVariable( 'ContentClassHasInput' ) )
        {
            $content['naming_pattern'] = '';
        }

        if( $http->hasPostVariable( $displayName ) )
        {
            $content['display_type'] = $http->postVariable( $displayName );
        }
        else if( $http->hasPostVariable( 'ContentClassHasInput' ) )
        {
            $content['display_type'] = 'line'; // default
        }

        if( $http->hasPostVariable( $negateName ) )
        {
            $negates = $http->postVariable( $negateName );
            $content['negates'] = array();

            foreach( $negates as $key => $value )
            {
                $content['negates'][$key] = true;
            }
        }
        else if( $http->hasPostVariable( 'ContentClassHasInput' ) )
        {
            $content['negates'] = array();
        }

        $regexp = $this->getRegularExpression( $content );
        $subPatternCount = 0;
        $subPatterns = array();

        foreach( $regexp as $expr )
        {
            $subPatternCount += @preg_match_all( "/\((?!\?\:)(.*)\)/U", $expr, $matches, PREG_PATTERN_ORDER );
            $subPatterns = array_merge( $subPatterns, $matches[1] );
        }

        $content['subpattern_count'] = $subPatternCount == false ? 0 : $subPatternCount;
        $content['subpatterns'] = $subPatterns;

        $classAttribute->setContent( $content );
        $classAttribute->store();

        return true;
    }

    function storeClassAttribute( &$classAttribute, $version )
    {
        $content = $classAttribute->content();

        $classAttribute->setAttribute( 'data_text5', serialize( $content ) );
    }

    function &classAttributeContent( &$classAttribute )
    {
        $content = unserialize( $classAttribute->attribute( 'data_text5' ) );

        if( !is_array( $content ) )
        {
            $content = array( 'regexp' => array(),
                              'error_messages' => array(),
                              'negates' => array(),
                              'preset' => array(),
                              'help_text' => '',
                              'subpattern_count' => 0,
                              'subpatterns' => array(),
                              'naming_pattern' => '',
                              'display_type' => 'line' );
        }

        if( isset( $content['pattern_selection'] ) )
        {
            $this->migratePatternSelection( $content );
        }

        if( !is_array( $content['regexp'] ) )
        {
            $content['regexp'] = array( $content['regexp'] );
        }

        if( !is_array( $content['preset'] ) )
        {
            $tmpPreset = array();

            if( !empty( $content['preset'] ) )
            {
                $tmpPreset[] = $content['preset'];
            }

            $content['preset'] = $tmpPreset;
        }

        if( !isset( $content['display_type'] ) )
        {
            $content['display_type'] = 'line';
        }

        if( !isset( $content['error_messages'] ) )
        {
            $content['error_messages'] = array();
        }

        if( !isset( $content['negates'] ) )
        {
            $content['negates'] = array();
        }

        return $content;
    }

    /*!
     Validates input on content object level
     \return EZ_INPUT_VALIDATOR_STATE_ACCEPTED or EZ_INPUT_VALIDATOR_STATE_INVALID if
             the values are accepted or not
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $status = $this->validateAttributeHTTPInput( $http, $base, $contentObjectAttribute, false );

        return $status;
    }

    /*!
     Fetches all variables from the object
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $textName = $base . "_hmregexpline_data_text_" . $contentObjectAttribute->attribute( 'id' );

        if( $http->hasPostVariable( $textName ) )
        {
            $text = $http->postVariable( $textName );
            $contentObjectAttribute->setContent( $text );
            $contentObjectAttribute->storeData();
            return true;
        }
        return false;
    }

    function storeObjectAttribute( &$contentObjectAttribute )
    {
        $text = $contentObjectAttribute->content();

        $contentObjectAttribute->setAttribute( 'data_text', $text );
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $text = $contentObjectAttribute->attribute( 'data_text' );

        return $text;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $text = $contentObjectAttribute->attribute( 'data_text' );
        $result = !empty( $text );

        return $result;
    }

    function validateAttributeHTTPInput( &$http, $base, &$objectAttribute, $isInformationCollector = false )
    {
        $textName = $base . "_hmregexpline_data_text_" . $objectAttribute->attribute( 'id' );
        $classAttribute =& $objectAttribute->contentClassAttribute();

        $required = false;
        $must_validate = ( $isInformationCollector == $classAttribute->attribute( 'is_information_collector' ) );

        if( method_exists( $objectAttribute, 'validateIsRequired' ) )
        {
            $required = $objectAttribute->validateIsRequired();
        }
        else
        {
            $required = ( $classAttribute->attribute( 'is_required' ) == 1 );
        }

        if( $http->hasPostVariable( $textName ) )
        {
            $text = $http->postVariable( $textName );
            $classContent = $classAttribute->content();

            if( empty( $text ) and ( $required === true && $must_validate === true ) )
            {
                $objectAttribute->setValidationError( ezi18n( 'extension/regexpline/datatype', 'This is a required field.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }

            if( !empty( $text ) )
            {
                $regexp = $this->getRegularExpression( $classContent );
                $text = $this->stripTags( $objectAttribute, $text );

                foreach( $regexp as $index => $expr )
                {
                    $doNegate = isset( $classContent['negates'][$index] );
                    $res = @preg_match( $expr, $text );

                    if( $doNegate === false )
                    {
                        $condition = ( $res === 0 );
                    }
                    else
                    {
                        $condition = ( $res === 1 );
                    }

                    if( $condition )
                    {
                        // No match
                        $msg = $this->getErrorMessage( $classContent, $index );

                        if( $msg === null )
                        {
                            $msg = ezi18n( 'extension/regexpline/datatype', 'Your input did not meet the requirements.' );
                        }

                        $objectAttribute->setValidationError( $msg );
                        return EZ_INPUT_VALIDATOR_STATE_INVALID;
                    }
                }
            }
        }
        else
        {
            if( $required === true && $must_validate === true )
            {
                $objectAttribute->setValidationError( ezi18n( 'extension/regexpline/datatype', 'This is a required field.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }

        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    function validateCollectionAttributeHTTPInput( &$http, $base, &$objectAttribute )
    {
        $status = $this->validateAttributeHTTPInput( $http, $base, $objectAttribute, true );

        return $status;
    }

    function fetchCollectionAttributeHTTPInput( &$collection, &$collectionAttribute, &$http, $base, &$contentObjectAttribute )
    {
        $textName = $base . "_hmregexpline_data_text_" . $contentObjectAttribute->attribute( 'id' );

        if( $http->hasPostVariable( $textName ) )
        {
            $text = $http->postVariable( $textName );
            $collectionAttribute->setAttribute( 'data_text', $text );
            return true;
        }

        return false;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $data = $contentObjectAttribute->attribute( 'data_text' );

        $data = $this->stripTags( $contentObjectAttribute, $data );

        return $data;
    }

    /*!
     Returns the value as it will be shown if this attribute is used in the object name pattern.
    */
    function title( &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $classContent = $classAttribute->content();
        $content = $contentObjectAttribute->content();
        $title = "";

        // Exit if the input is empty
        if( $content == '' )
        {
            return $content;
        }
        else
        {
            $content = $this->stripTags( $contentObjectAttribute, $content );
        }

        if( isset( $classContent['pattern_selection'] ) )
        {
            $this->migratePatternSelection( $classContent );
        }

        $regexp = $this->getRegularExpression( $classContent );
        $res = 0;
        $matchArray = array( $content );

        foreach( $regexp as $index => $expr )
        {
            $res += @preg_match( $expr, $content, $matches );
            unset( $matches[0] ); // We don't need this one
            $matchArray = array_merge( $matchArray, $matches );
        }

        // Only replace if there's at least a match
        if( (count( $matchArray ) - 1) == $classContent['subpattern_count'] )
        {
            if( $classContent['naming_pattern'] != '' )
            {
                $title = preg_replace( "/<([0-9]+)>/e", "\$matchArray[\\1]", $classContent['naming_pattern'] );
            }
            else
            {
                $title = $matchArray[0];
            }
        }

        return $title;
    }

    /*!
     \return true if the datatype can be indexed
    */
    function isIndexable()
    {
        return true;
    }

    function isInformationCollector()
    {
        return true;
    }

    function &sortKey( &$contentObjectAttribute )
    {
        $text = $contentObjectAttribute->content();

        return $text;
    }

    function &sortKeyType()
    {
        return 'string';
    }

    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $serializedContent = $classAttribute->attribute( 'data_text5' );

        $attributeParametersNode->appendChild( eZDOMDocument::createElementCDATANode( 'content', $serializedContent ) );
    }

    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $serializedContent = $attributeParametersNode->elementTextContentByName( 'content' );

        $classAttribute->setAttribute( 'data_text5', $serializedContent );
    }


    function getRegularExpression( &$classContent )
    {
        $regexp = $classContent['regexp'];

        if( !is_array( $classContent['preset'] ) )
        {
            $tmpPreset = array();

            if( !empty( $classContent['preset'] ) )
            {
                $tmpPreset[] = $classContent['preset'];
            }

            $classContent['preset'] = $tmpPreset;
        }

        if( count( $classContent['preset'] ) > 0 )
        {
            $tmpRegexp = array();
            $ini =& eZINI::instance( 'regexpline.ini' );
            $presets = $ini->variable( 'GeneralSettings', 'RegularExpressions' );

            foreach( $classContent['preset'] as $preset )
            {
                if( isset( $presets[$preset] ) )
                {
                    $tmpRegexp[$preset] = $presets[$preset];
                }
            }

            $regexp = $tmpRegexp;
        }

        if( !is_array( $regexp ) )
        {
            $regexp = array( $regexp );
        }

        return $regexp;
    }

    function getErrorMessage( &$classContent, $index )
    {
        $msg = null;

        if( isset( $classContent['error_messages'] ) && is_array( $classContent['error_messages'] ) )
        {
            if( isset( $classContent['error_messages'][$index] ) )
            {
                $msg = $classContent['error_messages'][$index];
            }
        }

        // Presets override
        if( count( $classContent['preset'] ) > 0 )
        {
            $ini =& eZINI::instance( 'regexpline.ini' );
            $presets = $ini->variable( 'GeneralSettings', 'RegularExpressions' );
            $messages = $ini->variable( 'GeneralSettings', 'ErrorMessages' );
            $msgIndex = $index;

            if( isset( $classContent['negates'][$index] ) )
            {
                $msgIndex .= '_negate';
            }

            if( isset( $presets[$index] ) && isset( $messages[$msgIndex] ) )
            {
                $msg = $messages[$msgIndex];
            }
        }

        return $msg;
    }

    function migratePatternSelection( &$classContent )
    {
        // Migrate the old pattern_selection to the newer naming_pattern
        $content['naming_pattern'] = '';

        foreach( $content['pattern_selection'] as $pattern )
        {
            $content['naming_pattern'] .= "<$pattern>";
        }

        unset( $content['pattern_selection'] );
    }

    function doStripTags( &$objectAttribute )
    {
        if( $this->KeepTags === null )
        {
            $regIni =& eZINI::instance( 'regexpline.ini' );

            if( $regIni->hasVariable( 'GeneralSettings', 'KeepTags' ) )
            {
                include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
                // Don't ask why they've put that function in there :-s

                $keepTags = $regIni->variable( 'GeneralSettings', 'KeepTags' );
                $keepTagsResult = array();

                foreach( $keepTags as $identifier )
                {
                    $keepTagsResult[] = eZContentObjectTreeNode::classAttributeIDByIdentifier( $identifier );
                }

                $this->KeepTags = $keepTagsResult;
            }
            else
            {
                return true;
            }
        }

        $shouldStrip = true;
        $classAttributeID =& $objectAttribute->attribute( 'contentclassattribute_id' );

        if( is_array( $this->KeepTags ) && in_array( $classAttributeID, $this->KeepTags ) )
        {
            $shouldStrip = false;
        }

        return $shouldStrip;
    }

    function stripTags( &$objectAttribute, $content )
    {
        if( $this->doStripTags( $objectAttribute ) === true )
        {
            $content = strip_tags( $content );
        }

        return $content;
    }
    
    function addClassErrorMessage( &$classAttribute, $message )
    {
    	$content =& $classAttribute->content();
        
        $content['class_validation_messages'][] = $message;
        
        $classAttribute->setContent( $content );
        $classAttribute->store();
    }
    
    function clearClassErrorMessages( &$classAttribute )
    {
    	$content =& $classAttribute->content();
        
        $content['class_validation_messages'] = array();
        
        $classAttribute->setContent( $content );
        $classAttribute->store();
    }

    var $KeepTags = null;
}

eZDataType::register( EZ_DATATYPESTRING_REGEXPLINE, "hmregexplinetype" );

?>
