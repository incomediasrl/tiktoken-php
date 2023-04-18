<?php

declare(strict_types=1);

namespace Yethee\Tiktoken\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yethee\Tiktoken\EncoderProvider;

use function dirname;

final class EncoderProviderTest extends TestCase
{
    /**
     * @param non-empty-string $modelName
     * @param non-empty-string $encoding
     * @dataProvider getEncoderForModelProvider
     */
    public function testGetEncoderForModel(string $modelName, string $encoding): void
    {
        $provider = new EncoderProvider();
        $provider->setVocabCache(dirname(__DIR__) . '/.cache/vocab');

        $encoder = $provider->getForModel($modelName);

        self::assertSame($encoding, $encoder->name);
    }

    public function testEncode(): void
    {
        $provider = new EncoderProvider();
        $provider->setVocabCache(dirname(__DIR__) . '/.cache/vocab');

        $encoder = $provider->get('p50k_base');
        self::assertSame([31373, 995], $encoder->encode('hello world'));

        $encoder = $provider->get('cl100k_base');
        self::assertSame([15339, 1917], $encoder->encode('hello world'));
    }

    /**
     * @return array
     *
     * @psalm-api
     */
    public function getEncoderForModelProvider(): array
    {
        return [
            ['text-davinci-003', 'p50k_base'],
            ['text-davinci-edit-001', 'p50k_edit'],
            ['gpt-3.5-turbo-0301', 'cl100k_base']
        ];
    }
}
