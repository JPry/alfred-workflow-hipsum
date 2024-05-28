<?php

declare( strict_types=1 );

namespace JPry\AlfredWorkflow\Hipsum;

use GuzzleHttp\Client;
use InvalidArgumentException;
use Throwable;

/**
 * Class Hipsum
 *
 * @since 1.0.0
 *
 * @see https://hipsum.co/the-api/
 *
 * @method $this paragraphs( int $paragraphs ) Defines the number of paragraphs to return.
 * @method $this sentences( int $sentences ) Defines the number of sentences to return. Used instead of paragraphs.
 * @method $this with_latin() Include latin works with the hipster words.
 */
class Hipsum {

	private string $url = 'https://hipsum.co/api/';

	private int $paragraphs = 5;

	private ?int $sentences = null;

	private string $type = 'hipster-centric';

	private Client $client;

	/**
	 * @param Client|null $client The Guzzle Client object.
	 */
	public function __construct( ?Client $client = null ) {
		$this->client = $client ?? new Client(
			[
				'base_uri' => $this->url,
			]
		);
	}

	/**
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @return $this
	 */
	public function __call( string $name, array $arguments ) {
		// We only care about the first argument.
		$argument = array_shift( $arguments );

		$this->$name = match ( $name ) {
			'paragraphs', 'sentences' => $argument,
			'with_latin'              => 'hipster-latin',
			default                   => throw new InvalidArgumentException( sprintf( 'Unknown method: %s', $name ) ),
		};

		return $this;
	}

	public function get(): string {
		$query = [
			'type' => $this->type,
		];

		if ( $this->sentences ) {
			$query['sentences'] = $this->sentences;
		} else {
			$query['paras'] = $this->paragraphs;
		}

		try {
			$response = $this->client->get( '', [ 'query' => $query ] );

			$body_parts = json_decode( $response->getBody()->getContents(), flags: JSON_THROW_ON_ERROR );

			return join( "\n\n", $body_parts );
		} catch ( Throwable $th ) {
			return '';
		}
	}
}
