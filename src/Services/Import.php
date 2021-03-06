<?php


namespace Guilty\Apsis\Services;


use Guilty\Apsis\Builders\CsvImport;
use Guilty\Apsis\Utils\BooleanFormatter;
use GuzzleHttp\RequestOptions;

class Import extends Service
{
    /**
     * Batch import multiple subscribers using a CSV formatted file.
     * NB! There is a file size limitation of 100 Mb so files larger than that should be in .zip format.
     * Also note that the method will return the Import ID, and the status of the import can later be polled using the GetImportStatus method.
     *
     * External files are downloaded asynchronously.
     * They should thereby be kept at their location until the GetImportStatus method reports that the import is completed.
     *
     * Use DemographicDataMapping parameter to selectively specify import file columns from 7 to 107 to map to demographic
     * data fields from 1 to 100.
     *
     * If DemographicDataMapping is provided, not mapped demographic data fields will not be imported.
     *
     * @param string|int $mailingListId Id of the mailing list that you want to import subscribers to
     * @param \Guilty\Apsis\Builders\CsvImport $csvImport
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createImportByCsv($mailingListId, CsvImport $csvImport)
    {
        $params = $csvImport->toArray();
        $params["MailingListId"] = $mailingListId;

        $endpoint = "/import/v4/csv";
        $response = $this->client->request("post", $endpoint, [
            \GuzzleHttp\RequestOptions::JSON => array_filter($params)
        ]);

        return $this->responseToJson($response);
    }

    /**
     * Batch import multiple subscribers using XML-data.
     *
     * NB! It is recommended that each batch should not be larger than around 2 000 subscribers
     * (there is a file size limitation of 5 Megabyte in place that could cause large batches to fail).
     *
     * 'XmlData' containing data about imported subscribers in XML format,
     * should be properly encoded depending on request format.
     *
     * The method requires use of demographic name mapping.
     *
     * @see http://se.apidoc.anpdm.com/Browse/Method/ImportService/CreateImportByXml
     * @param string|int $mailingListId Id of the mailing list that you want to import subscribers to
     * @param string $xmlData Subscribers data in xml format
     * @param bool $demographicNameMapping Should demographic data be mapped by Name (true) or index order (false)
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createImportByXml($mailingListId, $xmlData, $demographicNameMapping = false)
    {
        $demographicNameMapping = BooleanFormatter::toString($demographicNameMapping);

        $endpoint = "/v1/import/mailinglist/{$mailingListId}/demographicmapping/{$demographicNameMapping}";
        $response = $this->client->request("post", $endpoint, [
            RequestOptions::JSON => [
                "XmlData" => $xmlData
            ]
        ]);

        return $this->responseToJson($response);
    }

    /**
     * Gets the list of duplicate email addresses for a given import, which are ignored in the import process.
     * History is kept for 30 days, after which it is deleted.
     *
     * @param string|int $importId Id of the import
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getImportIgnoredDuplicates($importId)
    {
        $endpoint = "/import/v2/{$importId}/ignoredduplicates";
        $response = $this->client->request("get", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Gets the list of invalid email addresses for a given import.
     * History is kept for 30 days, after which it is deleted.
     *
     * @param string|int $importId Id of the import
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getImportInvalidEmails($importId)
    {
        $endpoint = "/import/v2/{$importId}/invalidemails";
        $response = $this->client->request("get", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Gets the list of Opt-Out all email addresses for a given import.
     * History is kept for 30 days, after which it is deleted.
     *
     * @param string|int $importId Id of the import
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getImportRecipientsOnOptOutAll($importId)
    {
        $endpoint = "/import/v2/{$importId}/recipientsonoptoutall";
        $response = $this->client->request("get", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Gets the list of Opt-Out list email addresses for a given import.
     * History is kept for 30 days, after which it is deleted.
     *
     * @param string|int $importId Id of the import
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getImportRecipientsOnOptOutList($importId)
    {
        $endpoint = "/import/v2/{$importId}/recipientsonoptoutlist";
        $response = $this->client->request("get", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Gets the result for a given import
     *
     * @param string|int $importId Id of the import
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getImportResults($importId)
    {
        $endpoint = "/import/v2/{$importId}/results";
        $response = $this->client->request("get", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Gets the status of the import that has been previously added to the importer queue.
     *
     * @param string|int $importId Id of the import
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getImportStatus($importId)
    {
        $endpoint = "/import/v2/{$importId}/status";
        $response = $this->client->request("get", $endpoint);

        return $this->responseToJson($response);
    }

}