<?php


namespace Guilty\Apsis\Services;


class Newsletter extends Service
{

    /**
     * Create a new newsletter.
     * If no subject is included, the name of the newsletter will become also its subject (Direct).
     *
     * @param array $newsletterData The newsletter object to create
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createNewsletter($newsletterData)
    {
        $endpoint = "/v1/newsletters/";
        $response = $this->client->request("post", $endpoint, [
            \GuzzleHttp\RequestOptions::JSON => $newsletterData
        ]);

        return $this->responseToJson($response);
    }

    /**
     * Delete a batch of newsletters, with list in body. (max 1000 per request) (Queued)
     *
     * @param string[]|int[] $newsletterIds List of Newsletter Ids to delete
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteMultipleNewsletters($newsletterIds)
    {
        if (count($newsletterIds) > 1000) {
            throw new \InvalidArgumentException("Max 1000 newsletters can be deleted per request.");
        }

        $endpoint = "/v1/newsletters/";
        $response = $this->client->request("delete", $endpoint, [
            \GuzzleHttp\RequestOptions::JSON => $newsletterIds
        ]);

        return $this->responseToJson($response);
    }

    /**
     * Delete a single newsletter.
     *
     * @param string|int $newsletterIds The newsletter to delete
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteSingleNewsletter($newsletterId)
    {
        $endpoint = "/v1/newsletters/{$newsletterId}";
        $response = $this->client->request("delete", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Get a list of all the newsletters.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllNewsletters()
    {
        $endpoint = "/v1/newsletters/all";
        $response = $this->client->request("post", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Returns all links for a newsletter (by NewsletterId).
     * Please note that dynamic links generated by Apsis
     * (e.g. ##TellAFriend## or ##OptOutAll##) will not be part of the result.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNewsletterLinks($newsletterId)
    {
        $endpoint = "/newsletters/v1/{$newsletterId}/links";
        $response = $this->client->request("get", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Returns a paginated list of all the newsletters for this account
     * 2016-05-19 v2: Optimized performance
     *
     * @param string|int $pageNumber The page in the result set to return, starts with 1
     * @param string|int $pageSize The size of each page result set, minimum 1
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNewslettersPaginated($pageNumber, $pageSize)
    {
        $endpoint = "/newsletters/v2/{$pageNumber}/{$pageSize}";
        $response = $this->client->request("get", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Get the HTML version of the newsletter.
     *
     * @param string|int $newsletterId The newsletter Id to retrieve.
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNewsletterWebVersionLink($newsletterId)
    {
        $endpoint = "/v1/newsletters/{$newsletterId}/webversion";
        $response = $this->client->request("get", $endpoint);

        return $this->responseToJson($response);
    }

    /**
     * Update an existing newsletter
     *
     * @param string|int $newsletterId The newsletter to update
     * @param array $newsletterData The newsletter object to create
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateNewsletter($newsletterId, $newsletterData)
    {
        $endpoint = "/v1/newsletters/{$newsletterId}";
        $response = $this->client->request("post", $endpoint, [
            \GuzzleHttp\RequestOptions::JSON => $newsletterData
        ]);

        return $this->responseToJson($response);
    }
}