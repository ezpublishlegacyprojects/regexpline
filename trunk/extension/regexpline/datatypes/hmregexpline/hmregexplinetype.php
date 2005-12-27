<?php

/*
    Regular Expression datatype for eZ publish 3.x
    Copyright (C) 2005  Hans Melis

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
  \version 1.0
  \date    Thursday 17 March 2005 2:22:55 pm
  \author  Hans Melis

  By using regexpline you can ... do stuff :)

*/

include_once( 'kernel/classes/ezdatatype.php' );

include_once( 'kernel/common/i18n.php' );

define( 'EZ_DATATYPESTRING_REGEXPLINE', 'hmregexpline' );

class hmregexplinetype extends eZDataType
{
    /*!
      Constructor
    */
    function hmregexplinetype()
    {
        $this->eZDataType( EZ_DATATYPESTRING_REGEXPLINE, ezi18n( 'extension/regexpline/datatype', 'Regular Expression Text', 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
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
        
        $regexp = $preset = '';

        if( $http->hasPostVariable( $regexpName ) )
        {
            $regexp = $http->postVariable( $regexpName );
        }

        if( $http->hasPostVariable( $presetName ) )
        {
            $preset = $http->postVariable( $presetName );
        }
        
        if( !empty( $preset ) )
        {
            $ini =& eZINI::instance( 'regexpline.ini' );
            $presets = $ini->variable( 'GeneralSettings', 'RegularExpressions' );

            if( isset( $presets[$preset] ) )
            {
            $regexp = $presets[$preset];
            }
        }        

        $check = @preg_match( $regexp, 'Dummy string' );
            
        if( $check === false )
        {
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
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
        $helpName = $base . "_hmregexpline_helptext_" . $classAttribute->attribute( 'id' );
        $patternName = $base . "_hmregexpline_patternselect_" . $classAttribute->attribute( 'id' );
        $presetName = $base . "_hmregexpline_preset_" . $classAttribute->attribute( 'id' ); 

        $content = $classAttribute->content();

        if( $http->hasPostVariable( $regexpName ) )
        {
            $content['regexp'] = $http->postVariable( $regexpName );
        }

        if( $http->hasPostVariable( $presetName ) )
        {
            $content['preset'] = $http->postVariable( $presetName );
        }
        
        if( $http->hasPostVariable( $helpName ) )
        {
            $content['help_text'] = $http->postVariable( $helpName );
        }
        
        if( $http->hasPostVariable( $patternName ) )
        {
            $content['pattern_selection'] = $http->postVariable( $patternName );
        }
        else if( $http->hasPostVariable( 'ContentClassHasInput' ) )
        {
            $content['pattern_selection'] = array();
        }

        $regexp = $content['regexp'];

        if( !empty( $content['preset'] ) )
        {
            $ini =& eZINI::instance( 'regexpline.ini' );
            $presets = $ini->variable( 'GeneralSettings', 'RegularExpressions' );

            if( isset( $presets[$content['preset']] ) )
            {
                $regexp = $presets[$content['preset']];

                // Clear the regular expression in the content
                $content['regexp'] = '';
            }
        }
    
        $subPatternCount = @preg_match_all( "/\((?!\?\:)/", $regexp, $matches );
        
        $content['subpattern_count'] = $subPatternCount == false ? 0 : $subPatternCount;
        
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
            $content = array( 'regexp' => '',
                              'preset' => '',
                              'help_text' => '',
                              'subpattern_count' => 0,
                              'pattern_selection' => array() );
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
                $objectAttribute->setValidationError( ezi18n( 'extension/regexpline/datatype', 'This is a required field which means you can\'t leave it empty' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
            
        if( !empty( $text ) and @preg_match( $this->getRegularExpression( $classContent ), $text ) === 0 )
            {
                // No match
                $objectAttribute->setValidationError( ezi18n( 'extension/regexpline/datatype', 'Your input did not meet the requirements.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
        else
        {
            if( $required === true && $must_validate === true )
            {
                $objectAttribute->setValidationError( ezi18n( 'extension/regexpline/datatype', 'This is a required field which means you can\'t leave it empty' ) );
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
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Returns the value as it will be shown if this attribute is used in the object name pattern.
    */
    function title( &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $classContent = $classAttribute->content();
        $content = $contentObjectAttribute->content();
        $index = "";

        if( is_array( $classContent['pattern_selection'] ) and count( $classContent['pattern_selection'] ) > 0 )
        {
            $res = @preg_match( $this->getRegularExpression( $classContent ), $content, $matches );

            if( $res !== false )
            {
                foreach( $classContent['pattern_selection'] as $patternIndex )
                {
                    if( isset( $matches[$patternIndex] ) )
                    {
                        $index .= $matches[$patternIndex];
                    }
                }
            }
        }
        else
        {
            $index = $content;
        }

        return $index;
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

        if( !empty( $classContent['preset'] ) )
        {
            $ini =& eZINI::instance( 'regexpline.ini' );
            $presets = $ini->variable( 'GeneralSettings', 'RegularExpressions' );

            if( isset( $presets[$classContent['preset']] ) )
            {
                $regexp = $presets[$classContent['preset']];
            }
        
        }

        return $regexp;
    }
}

eZDataType::register( EZ_DATATYPESTRING_REGEXPLINE, "hmregexplinetype" );

?>
