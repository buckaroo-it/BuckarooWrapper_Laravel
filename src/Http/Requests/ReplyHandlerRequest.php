<?php

namespace Buckaroo\Laravel\Http\Requests;

use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\ResponseParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReplyHandlerRequest extends FormRequest
{
    protected ?string $message;
    protected ResponseParserInterface $data;

    public function authorize(): bool
    {
        /* @var ResponseParserInterface $data */
        $this->data = ResponseParser::make($this->getRawData());

        if (!$this->validateBody()) {
            $this->message = 'Invalid signature';

            return false;
        }

        return true;
    }

    /**
     * Get raw POST data preserving trailing spaces for signature verification.
     */
    protected function getRawData(): array
    {
        $rawContent = $this->getContent();

        if (!$rawContent) {
            return $this->all();
        }

        if ($this->isJson()) {
            $decoded = json_decode($rawContent, true);

            return is_array($decoded) ? $decoded : $this->all();
        }

        parse_str($rawContent, $parsed);

        return !empty($parsed) ? $parsed : $this->all();
    }

    protected function validateBody()
    {
        return Buckaroo::api()->validateBody(
            $this->data->getOriginalItems(),
            $this->header('Authorization') ?? '',
            route('buckaroo.push')
        );
    }

    protected function failedAuthorization()
    {
        throw new HttpException(400, $this->message);
    }
}
