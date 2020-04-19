# PHP client library for consuming the Buildkite API

![](https://github.com/bbaga/buildkite-php/workflows/Continous%20Integration/badge.svg)
![](https://shepherd.dev/github/bbaga/buildkite-php/coverage.svg)

**Documentations is work in progress.**

## Usage

[Direct API calls](#direct-api-calls)
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
use BuildkiteApi\Api\RestApi;
use GuzzleHttp\Client;

require __DIR__.'/vendor/autoload.php';

$client = new Client();
$api = new RestApi($client, MY_BUILDKITE_API_TOKEN');
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
