# PHP client library for consuming the Buildkite API

![](https://github.com/bbaga/buildkite-php/workflows/Continous%20Integration/badge.svg)
![](https://shepherd.dev/github/bbaga/buildkite-php/coverage.svg)
![](https://codecov.io/gh/bbaga/buildkite-php/branch/master/graph/badge.svg)

**Documentations is work in progress.**

## Installation
```shell script
composer require bbaga/buildkite-php
```

## Usage

* [Interacting with Buildkite's REST API](#interacting-with-buildkites-rest-api)
  * [Example of traversing through resources](#example-of-traversing-through-resources)
  * [Getting straight to the point](#getting-straight-to-the-point)
* [Direct API calls](#direct-api-calls)
  * [Organizations API](#organizations-api)
    * [List the ](#list-the-organizations)
    * [Get a specific organization](#get-a-specific-organization)
  * [Pipelines API](#pipelines-api)
    * [List pipelines in an organization](#list-pipelines-in-an-organizations)
    * [Get a specific pipeline](#get-a-specific-pipeline)
    * [Create a pipeline](#create-a-pipeline)
    * [Update a pipeline](#update-a-pipeline)
    * [Delete a pipeline](#delete-a-pipelne)
  * [Builds API](#builds-api)
    * [List all builds across all the organizations](#list-all-builds-across-all-the-organizations)
    * [Get a specific build](#get-a-specific-build)
    * [Get builds in an organization](#get-builds-in-an-organization)
    * [Get builds for a pipeline](#get-builds-for-a-pipeline)
    * [Create new build](#create-a-new-build)
    * [Cancel a running build](#cancel-a-running-build)
    * [Restarting a build](#restarting-a-build)
  * [Jobs API](#jobs-api)
  * [Artifacts API](#artifacts-api)
  * [Agents API](#agents-api)
  * [Annotations API](#annotations-api)
  * [Users API](#users-api)
  * [Emojis API](#emojis-api)

### Setting up the RestApi object
```php
use bbaga\BuildkiteApi\Api\RestApi;

/** @var \BuildkiteApi\Api\HttpClientInterface $client */
$client = new MyHttpClient(); 

$api = new RestApi($client, 'MY_BUILDKITE_API_TOKEN');
```

`\BuildkiteApi\Api\HttpClientInterface` implementation is available in the [`bbaga/buildkite-php-guzzle-client`](https://github.com/bbaga/buildkite-php-guzzle-client) package.
`\BuildkiteApi\Api\HttpClientInterface` is available in the [`bbaga/buildkite-php-http-interface`](https://github.com/bbaga/buildkite-php-http-interface) package.

### Interacting with Buildkite's REST API

#### Example of traversing through resources
```php
use bbaga\BuildkiteApi\Api\GuzzleClient;
use bbaga\BuildkiteApi\Api\Rest\Fluent;
use bbaga\BuildkiteApi\Api\RestApi;

$client = new GuzzleClient();
$api = new RestApi($client, 'MY_BUILDKITE_API_TOKEN');

/** Getting all the organizations that are visible with the TOKEN */
/** @var Fluent\Organization[] $organizations */
$organizations = (new Fluent\Organizations($api))->get();

/** @var Fluent\Organization $organization */
$organization = $organizations[0];

/** @var Fluent\Pipeline $pipelines */
$pipelines = $organization->getPipelines();

/** @var Fluent\Pipeline $pipeline */
$pipeline = $pipelines[0];

/** @var Fluent\Build[] $builds */
$builds = $pipeline->getBuilds();

/** @var Fluent\Build $build */
$build = $builds[0];

/** @var Fluent\Job[] $jobs */
$jobs = $build->getJobs();

/** @var Fluent\Emoji[] $emojis */
$emojis = $organizations[0]->getEmojis();

/** @var Fluent\Agent[] $emojis */
$agents = $organizations[0]->getAgents();
```

#### Getting straight to the point

Fetching data for a specific build without traversing through the hierarchy.

```php
use bbaga\BuildkiteApi\Api\GuzzleClient;
use bbaga\BuildkiteApi\Api\Rest\Fluent;
use bbaga\BuildkiteApi\Api\RestApi;

$client = new GuzzleClient();
$api = new RestApi($client, 'MY_BUILDKITE_API_TOKEN');

/**
 * Builds are identified by the follwoing three values 
 */
$organizationSlug = 'my-org';
$pipelineSlug = 'my-pipeline';
$buildNumber = 23;

$organization = new Fluent\Organization($api, ['slug' => $organizationSlug]);
$pipeline = new Fluent\Pipeline($api, $organization, ['slug' => $pipelineSlug]);
$build = new Fluent\Build($api, $organization, ['number' => $buildNumber, 'pipeline' => $pipeline]);

$build->fetch()->getJobs();
```

### Direct API calls

### Organizations API

Organizations related methods are exposed via `$api->organization()`

Detailed documentation for the Organizations API is available [here](https://buildkite.com/docs/apis/rest-api/organizations)

#### List the organizations

```php
$api->organization()->list();
```

#### Get a specific organization
```php
$api->organization()->get('my-organization');
```

### Pipelines API

Pipelines related methods are exposed via `$api->pipeline()`

Detailed documentation for the Pipelines API is available [here](https://buildkite.com/docs/apis/rest-api/pipelines)

#### List pipelines in an organizations
```php
$api->pipeline()->list('my-organizations');
```

#### Get a specific pipeline
```php
$api->pipeline()->get('my-organization', 'my-pipeline');
```

#### Create a pipeline
```php
$pipelineData = [
    'name' => 'My Pipeline',
    'repository' => 'git@github.com:acme-inc/my-pipeline.git',
    'steps' => [
        [
            'type' => 'script',
            'name' => 'Build :package:',
            'command' => 'script/release.sh',
        ],
    ],
];

$api->pipeline()->create('my-organization', $pipelineData);
```

#### Update a pipeline
```php
$pipelineData = [
    'repository' => 'git@github.com:acme-inc/new-repo.git',
];

$api->pipeline()->update('my-organization', 'my-pipelines', $pipelineData);
```

#### Delete a pipelne
```php
$api->pipeline()->delete('my-organization', 'my-pipeline');
```

### Builds API

Builds related methods are exposed via `$api->build()`

Detailed documentation for the Builds API is available [here](https://buildkite.com/docs/apis/rest-api/builds)

#### List all builds across all the organizations
```php
$api->build()->listAll($queryParameters);
```

#### Get a specific build
```php
$buildNumber = 1;
$api->build()->get('my-organization', 'my-pipeline', $buildNumber);
```

#### Get builds in an organization
```php
$api->build()->getByOrganization('my-organization', $queryParameters);
```

#### Get builds for a pipeline
```php
$api->build()->getByPipeline('my-organization', 'my-pipeline', $queryParameters);
```

#### Create a new build
```php
$buildSettings = [
    'commit' => 'abcd0b72a1e580e90712cdd9eb26d3fb41cd09c8',
    'branch' => 'master',
    'message' => 'Testing all the things :rocket:',
    'author' => [
        'name' => 'Keith Pitt',
        'email' => 'me@keithpitt.com',
    ],
    'env' => [
        'MY_ENV_VAR' => 'some_value',
    ],
    'meta_data' => [
        'some build data' => 'value',
        'other build data' => true,
    ],
];

$api->build()->create('my-organization', 'my-pipeline', $buildSettings);
```

#### Cancel a running build
```php
$buildNumber = 12;
$api->build()->cancel('my-organization', 'my-pipeline', $buildNumber);
```

#### Restarting a build
```php
$buildNumber = 12;
$api->build()->rebuild('my-organization', 'my-pipeline', $buildNumber);
```

### Jobs API

Jobs related methods are exposed via `$api->job()`

Detailed documentation for the Jobs API is available [here](https://buildkite.com/docs/apis/rest-api/jobs)

### Artifacts API

Jobs related methods are exposed via `$api->artifact()`

Detailed documentation for the Artifacts API is available [here](https://buildkite.com/docs/apis/rest-api/artifacts)

### Agents API

Agents related methods are exposed via `$api->agent()`

Detailed documentation for the Agents API is available [here](https://buildkite.com/docs/apis/rest-api/agents)

### Annotations API

Annotations related methods are exposed via `$api->annotation()`

Detailed documentation for the Annotations API is available [here](https://buildkite.com/docs/apis/rest-api/annotations)

### Users API

Users related methods are exposed via `$api->user()`

Detailed documentation for the Users API is available [here](https://buildkite.com/docs/apis/rest-api/users)

### Emojis API

Emojis related methods are exposed via `$api->emoji()`

Detailed documentation for the Users API is available [here](https://buildkite.com/docs/apis/rest-api/emojis)

## Contribution

### Testing

```shell script
make test
```

### Integration testing
A Buildkite account is required for integration testing and the following environment variables must be set.

* `BK_TEST_TOKEN`
* `BK_TEST_ORG`
* `BK_TEST_PREFIX`
* `GITHUB_REF`

These can be set in the `phpunit.xml` by making a copy of `phpunit.xml.dist` and extending it with the following snippet
```xml
    <php>
        <env name="BK_TEST_TOKEN" value="my-buildkite-api-token"/>
        <env name="BK_TEST_ORG" value="my-organization-slug"/>
        <env name="BK_TEST_PREFIX" value="something-uniqe"/>
        <env name="GITHUB_REF" value="refs/heads/master"/>
    </php>
```

Once the environment variables are set the test suite can be started

```shell script
make integration
```
