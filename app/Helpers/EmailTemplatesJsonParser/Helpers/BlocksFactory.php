<?php

namespace App\Helpers\EmailTemplatesJsonParser\Helpers;

use App\Helpers\EmailTemplatesJsonParser\Blocks\AttachmentsList;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Avatar;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Button;
use App\Helpers\EmailTemplatesJsonParser\Blocks\ClientPanelLink;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Columns;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Divider;
use App\Helpers\EmailTemplatesJsonParser\Blocks\EmailLayout;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Heading;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Html;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Image;
use App\Helpers\EmailTemplatesJsonParser\Blocks\OfferLink;
use App\Helpers\EmailTemplatesJsonParser\Blocks\PropertiesList;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Spacer;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Text;
use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;

class BlocksFactory
{
    public static function createBlock(string $type, array $decoded_json, array $layout = null): Block
    {
        switch ($type) {
            case BlockTypes::ATTACHMENTS_LIST:
                return new AttachmentsList($decoded_json);
            case BlockTypes::AVATAR:
                return new Avatar($decoded_json);
            case BlockTypes::BUTTON:
                return new Button($decoded_json);
            case BlockTypes::CLIENT_PANEL_LINK:
                return new ClientPanelLink($decoded_json);
            case BlockTypes::COLUMNS:
                return new Columns($decoded_json, $layout);
            case BlockTypes::DIVIDER:
                return new Divider($decoded_json);
            case BlockTypes::EMAIL_LAYOUT:
                return new EmailLayout($decoded_json);
            case BlockTypes::HEADING:
                return new Heading($decoded_json);
            case BlockTypes::HTML:
                return new Html($decoded_json);
            case BlockTypes::IMAGE:
                return new Image($decoded_json);
            case BlockTypes::OFFER_LINK:
                return new OfferLink($decoded_json);
            case BlockTypes::SPACER:
                return new Spacer($decoded_json);
            case BlockTypes::TEXT:
                return new Text($decoded_json);
            case BlockTypes::PROPERTIES_LIST:
                return new PropertiesList($decoded_json);
            default:
                throw new \Exception('Block type not found "' . $type . '"');
        }
    }
}
