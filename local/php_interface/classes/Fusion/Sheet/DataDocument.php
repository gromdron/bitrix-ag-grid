<?php


namespace Fusion\Sheet;

use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ArgumentException;
use Fusion\Sheet\DataTable;
use Bitrix\Main\Loader;

if ( ! Loader::includeModule( 'bizproc' ) )
{
    return;
}

/**
 * Class DataDocument - Описывает типы документов.
 * Определен один тип документа с идентификатором  "sheet".
 *
 * @package Fusion\Sheet
 */
class DataDocument implements \IBPWorkflowDocument
{


    /**
     * @return array Кортеж из трех элементов:
     *  код модуля, полное квалифицированное имя класса документа, код типа документа.
     */
    static public function getComplexDocumentType()
    {
        return [ 'fusion.sheet', self::class, 'sheet' ];
    }

    /**
     * @param $sheetId Идентификатор документа.
     *
     * @return array Кортеж из трех элементов:
     *  код модуля, полное квалифицированное имя класса документа, идентификатор документа.
     */
    static public function getComplexDocumentId( $sheetId )
    {
        return [ 'fusion.sheet', self::class, $sheetId ];
    }

    /**
     * @param $sheetId
     *
     * @return string
     */
    static public function GetDocumentType( $sheetId )
    {
        return 'sheet';
    }

    /**
     * @param mixed $documentId
     *
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     */
    static public function GetDocument( $documentId )
    {
        if ( intval( $documentId ) <= 0 )
        {
            throw new ArgumentException( 'Invalid sheet ID.', 'documentId' );
        }

        $dbSheet = DataTable::getById( $documentId );
        $sheet = $dbSheet->fetch();

        return self::convertSheetToBp( $sheet );
    }

    /**
     * @param mixed $documentType
     *
     * @return array
     */
    static public function GetDocumentFields( $documentType )
    {
        return [
            'ROW_NUMBER' => [
                'Name' => 'Номер строки',
                'Type' => FieldType::INT,
                'Filterable' => true,
                'Editable' => false,
                'Required' => false,
            ],
            'CELL_A' => [
                'Name' => 'A',
                'Type' => FieldType::STRING,
                'Filterable' => true,
                'Editable' => true,
                'Required' => true,
            ],
            'CELL_B' => [
                'Name' => 'B',
                'Type' => FieldType::STRING,
                'Filterable' => true,
                'Editable' => true,
                'Required' => true,
            ],
            'CELL_C' => [
                'Name' => 'C',
                'Type' => FieldType::STRING,
                'Filterable' => true,
                'Editable' => true,
                'Required' => true,
            ],
            'CELL_D' => [
                'Name' => 'D',
                'Type' => FieldType::STRING,
                'Filterable' => true,
                'Editable' => true,
                'Required' => true,
            ],
            'CELL_E' => [
                'Name' => 'E',
                'Type' => FieldType::STRING,
                'Filterable' => true,
                'Editable' => true,
                'Required' => true,
            ],
            'CELL_F' => [
                'Name' => 'F',
                'Type' => FieldType::STRING,
                'Filterable' => true,
                'Editable' => true,
                'Required' => true,
            ],
            'IS_SYNCED' => [
                'Name' => 'IS_SYNCED',
                'Type' => FieldType::STRING,
                'Filterable' => true,
                'Editable' => true,
                'Required' => true,
            ],
        ];
    }

    /**
     * @param       $parentDocumentId
     * @param array $arFields
     *
     * @return mixed
     */
    static public function CreateDocument( $parentDocumentId, $arFields )
    {
        $result = DataTable::add( self::convertSheetFromBp( $arFields ) );

        if ( $result->isSuccess() )
        {
            \CBPDocument::AutoStartWorkflows(
                self::getComplexDocumentType(),
                \CBPDocumentEventType::Create,
                self::getComplexDocumentId( $result->getId() ),
                [],
                $errors
            );
        }

        return $result->getId;
    }

    /**
     * @param mixed $documentId
     * @param array $arFields
     */
    static public function UpdateDocument( $documentId, $arFields )
    {
        $result = DataTable::update( $documentId, self::convertSheetFromBp() );

        if ( $result->isSuccess() )
        {
            \CBPDocument::AutoStartWorkflows(
                self::getComplexDocumentType(),
                \CBPDocumentEventType::Edit,
                self::getComplexDocumentId( $documentId ),
                [],
                $errors
            );
        }
    }

    /**
     * @param mixed $documentId
     */
    static public function DeleteDocument( $documentId )
    {
        DataTable::delete( $documentId );
    }

    /**
     * @param mixed $documentId
     *
     * @return bool|void
     */
    static public function PublishDocument( $documentId )
    {
        return false;
    }

    /**
     * @param mixed $documentId
     *
     * @return bool|void
     */
    static public function UnpublishDocument( $documentId )
    {
        return false;
    }

    /**
     * @param mixed  $documentId
     * @param string $workflowId
     *
     * @return bool
     */
    static public function LockDocument( $documentId, $workflowId )
    {
        return true;
    }

    /**
     * @param mixed  $documentId
     * @param string $workflowId
     *
     * @return bool
     */
    static public function UnlockDocument( $documentId, $workflowId )
    {
        return true;
    }

    /**
     * @param mixed  $documentId
     * @param string $workflowId
     *
     * @return bool
     */
    static public function IsDocumentLocked( $documentId, $workflowId )
    {
        return false;
    }

    /**
     * TODO: Возвращаем истину только для теста. Метод должен проверять права пользователя.
     *
     *
     * @param int   $operation Операция, право на выполнение которой проверяется. Могут быть переданы следующие константы:
     *                         CBPCanUserOperateOperation::ViewWorkflow
     *                         CBPCanUserOperateOperation::StartWorkflow
     *                         CBPCanUserOperateOperation::WriteDocument
     *                         CBPCanUserOperateOperation::ReadDocument
     * @param int   $userId    Идентификатор пользователя, от имени которого предполагается выполнить операцию.
     * @param mixed $documentId  Идентификатор документа (не комплексный).
     * @param array $arParameters  Вспомогательные параметры, например:
     *                             DocumentStates - массив состояний БП данного документа;
     *                             WorkflowId - код бизнес-процесса.
     *
     * @return bool
     */
    static public function CanUserOperateDocument( $operation, $userId, $documentId, $arParameters = [] )
    {
        return true;
    }

    /**
     * @param int   $operation Операция, право на выполнение которой проверяется. Могут быть переданы следующие константы:
     *                         CBPCanUserOperateOperation::WriteDocument
     *                         CBPCanUserOperateOperation::CreateWorkflow
     * @param int   $userId
     * @param       $documentType
     * @param array $arParameters
     *
     * @return bool
     */
    static public function CanUserOperateDocumentType( $operation, $userId, $documentType, $arParameters = [] )
    {
        return true;
    }

    /**
     * @param mixed $documentId
     *
     * @return string
     */
    static public function GetDocumentAdminPage( $documentId )
    {
        return \CComponentEngine::makePathFromTemplate(
            '', // Ссылка страницы детального просмотра
            [ 'STORE_ID' => $documentId ]
        );
    }

    /**
     * @param mixed $documentId
     * @param       $historyIndex
     *
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     */
    static public function GetDocumentForHistory( $documentId, $historyIndex )
    {
        return self::GetDocument( $documentId );
    }

    /**
     * @param mixed $documentId
     * @param array $arDocument
     *
     * @return array|void
     */
    static public function RecoverDocumentFromHistory( $documentId, $arDocument )
    {
        DataTable::update( $documentId, self::convertSheetFromBp( $arDocument ) );
    }

    /**
     * @param mixed $documentType
     *
     * @return array
     */
    static public function GetAllowableOperations( $documentType )
    {
        return [];
    }

    /**
     * @param mixed $documentType
     *
     * @return array|void
     */
    static public function GetAllowableUserGroups( $documentType )
    {
        // TODO: Implement GetAllowableUserGroups() method.
    }

    /**
     * @param mixed $group
     * @param mixed $documentId
     *
     * @return array|void
     */
    static public function GetUsersFromUserGroup( $group, $documentId )
    {
        // TODO: Implement GetUsersFromUserGroup() method.
    }

    /**
     * Метод конвертирует данные листа в формат необходимый БП.
     *
     * @param $sheet
     *
     * @return mixed
     */
    static private function convertSheetToBp( $sheet )
    {
        if ( isset( $sheet[ 'ASSIGNED_BY_ID' ] ) )
        {
            $sheet[ 'ASSIGNED_BY_ID' ] = 'user_' . $sheet[ 'ASSIGNED_BY_ID' ];
        }
        return $sheet;
    }

    /**
     * @param $sheet
     *
     * @return
     */
    static private function convertSheetFromBp( $sheet )
    {
        if ( isset( $sheet[ 'ASSIGNED_BY_ID' ] ) )
        {
            $sheet[ 'ASSIGNED_BY_ID' ] = str_replace( 'user_', '', $sheet[ 'ASSIGNED_BY_ID' ] );
        }
        return $sheet;
    }
}