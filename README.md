# PHP client library for consuming the Buildkite API

![](https://github.com/bbaga/buildkite-php/workflows/Continous%20Integration/badge.svg)
![](https://shepherd.dev/github/bbaga/buildkite-php/coverage.svg)
![](https://codecov.io/gh/bbaga/buildkite-php/branch/master/graph/badge.svg)

## Installation
```shell script
composer require bbaga/buildkite-php
```

## Usage
* [Setting up the API objects](#setting-up-the-api-objects)
  * [REST API](#rest-api)
  * [GraphQL API](#graphql-api)
* [Interacting with Buildkite's GraphQL API](#interacting-with-buildkites-graphql-api)
* [Interacting with Buildkite's REST API](#interacting-with-buildkites-rest-api)
  * [Example of traversing through resources](#example-of-traversing-through-resources)
  * [Accessing resources without traversing](#accessing-resources-without-traversing)
  * [Creating a new pipeline](#creating-a-new-pipeline)
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
    * [Retry a job](#retry-a-job)
    * [Unblock a job](#unblock-a-job)
    * [Get logs for a job](#get-logs-for-a-job)
    * [Delete logs of a job](#delete-logs-of-a-job)
    * [Get the environment variables from a job](#get-the-environment-variables-from-a-job)
  * [Artifacts API](#artifacts-api)
    * [Get artifacts uploaded from a build](#get-artifacts-uploaded-from-a-build)
    * [Get artifacts uploaded from a job](#get-artifacts-uploaded-from-a-job)
    * [Get a specific artifact](#get-a-specific-artifact)
    * [Delete a specific artifact](#delete-a-specific-artifact)
  * [Agents API](#agents-api)
    * [List agents for an organization](#list-agents-for-an-organization)
    * [Get a specific agent](#get-a-specific-agent)
    * [Stop an agent](#stop-an-agent)
  * [Annotations API](#annotations-api)
    * [Get annotations uploaded by a build](#get-annotations-uploaded-by-a-build)
  * [Users API](#users-api)
    * [Get current user](#get-current-user)
  * [Emojis API](#emojis-api)
    * [List available emojis](#list-available-emojis)

### Setting up the API objects

`\Psr\Http\Client\ClientInterface` implementation is available in the [`bbaga/buildkite-php-guzzle-client`](https://github.com/bbaga/buildkite-php-guzzle-client) package.

#### Rest API
```php
use bbaga\BuildkiteApi\Api\RestApi;

/** @var \Psr\Http\Client\ClientInterface $client */
$client = new MyHttpClient(); 

$api = new RestApi($client, 'MY_BUILDKITE_API_TOKEN');
```

### GraphQL Api
```php
use bbaga\BuildkiteApi\Api\GraphQLApi;

/** @var \Psr\Http\Client\ClientInterface $client */
$client = new MyHttpClient(); 

$api = new GraphQLApi($client, 'MY_BUILDKITE_API_TOKEN');
```

### Interacting with Buildkite's GraphQL API
```php
use bbaga\BuildkiteApi\Api\GraphQLApi;
use bbaga\BuildkiteApi\Api\GuzzleClient;

$query = '
    query example($slug: ID!, $first: Int){
        viewer { user { name } }
        
        organization(slug: $slug) {
            pipelines(first: $first) {
                edges {
                    node {
                        id
                        slug
                    }
                }
            }
        } 
    }';

$variables = json_encode(['slug' => 'my-org', 'first' => 5]);

$client = new GuzzleClient();
$api = new GraphQLApi($client, 'MY_BUILDKITE_API_TOKEN');

$api->getResponseBody($api->post($query, $variables));
```

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

#### Accessing resources without traversing

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

#### Creating a new pipeline

```php
use bbaga\BuildkiteApi\Api\GuzzleClient;
use bbaga\BuildkiteApi\Api\Rest\Fluent;
use bbaga\BuildkiteApi\Api\RestApi;

$client = new GuzzleClient();
$api = new RestApi($client, 'MY_BUILDKITE_API_TOKEN');

$organization = new Fluent\Organization($api, ['slug' => 'my-org']);
$pipeline = $organization->createPipeline(
    [
        'name' => 'my-pipeline',
        'repository' => 'git@github.com:some/repo.git',
        'steps' => [
            [
                'type' => 'script',
                'name' => 'upload artifact',
                'command' => 'echo "Hello" > artifact.txt \
                 && buildkite-agent artifact upload artifact.txt \
                 && cat artifact.txt | buildkite-agent annotate --style "success" --context "junit"',
            ],
            [
                'type' => 'manual',
                'name' => 'Needs to be unblocked',
                'command' => 'echo "Unblocked!"',
            ],
        ]
    ]
);

/**
 * Pipeline is ready, we can kick off the first build
 */
$buildSettings = [
    'commit' => 'HEAD',
    'branch' => 'master',
    'message' => 'Testing all the things :rocket:',
];

$pipeline->createBuild($buildSettings);
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

#### Retry a job
```php
$buildNumber = 12;
$jobId = '0738da5f-0372-4b02-a1cb-f07a12fbcdcd';

$api->job()->retry('my-organization', 'my-pipeline', $buildNumber, $jobId);
```

#### Unblock a job
```php
$buildNumber = 12;
$jobId = '0738da5f-0372-4b02-a1cb-f07a12fbcdcd';

$api->job()->unblock('my-organization', 'my-pipeline', $buildNumber, $jobId);
```

#### Get logs for a job
```php
$buildNumber = 12;
$jobId = '0738da5f-0372-4b02-a1cb-f07a12fbcdcd';

$api->job()->getLogOutput('my-organization', 'my-pipeline', $buildNumber, $jobId);
```

#### Delete logs of a job
```php
$buildNumber = 12;
$jobId = '0738da5f-0372-4b02-a1cb-f07a12fbcdcd';

$api->job()->deleteLogOutput('my-organization', 'my-pipeline', $buildNumber, $jobId);
```

#### Get the environment variables from a job
```php
$buildNumber = 12;
$jobId = '0738da5f-0372-4b02-a1cb-f07a12fbcdcd';

$api->job()->getEnvironmentVariables('my-organization', 'my-pipeline', $buildNumber, $jobId);
```

### Artifacts API

Jobs related methods are exposed via `$api->artifact()`

Detailed documentation for the Artifacts API is available [here](https://buildkite.com/docs/apis/rest-api/artifacts)

#### Get artifacts uploaded from a build
```php
$buildNumber = 12;
$api->artifact()->getByBuild('my-organization', 'my-pipeline', $buildNumber);
```

#### Get artifacts uploaded from a job
```php
$buildNumber = 12;
$jobId = '0738da5f-0372-4b02-a1cb-f07a12fbcdcd';

$api->artifact()->getByJob('my-organization', 'my-pipeline', $buildNumber, $jobId);
```

#### Get a specific artifact
```php
$buildNumber = 12;
$jobId = '0738da5f-0372-4b02-a1cb-f07a12fbcdcd';
$artifactId = '567038da5f03724b02a1cbf07a12fbcedfg';

$api->artifact()->get(
    'my-organization',
    'my-pipeline',
    $buildNumber,
    $jobId,
    $artifactId
);
```

#### Delete a specific artifact
```php
$buildNumber = 12;
$jobId = '0738da5f-0372-4b02-a1cb-f07a12fbcdcd';
$artifactId = '567038da5f03724b02a1cbf07a12fbcedfg';

$api->artifact()->delete(
    'my-organization',
    'my-pipeline',
    $buildNumber,
    $jobId,
    $artifactId
);
```

### Agents API

Agents related methods are exposed via `$api->agent()`

Detailed documentation for the Agents API is available [here](https://buildkite.com/docs/apis/rest-api/agents)

#### List agents for an organization
```php
$api->agent()->list('my-organization');
```

#### Get a specific agent
```php
$agentId = '1d633306-de28-4944-ad84-fde0d50a6c9e';
$api->agent()->list('my-organization', $agentId);
```

#### Stop an agent
```php
$agentId = '1d633306-de28-4944-ad84-fde0d50a6c9e';
$api->agent()->list('my-organization', $agentId);
```

### Annotations API

Annotations related methods are exposed via `$api->annotation()`

Detailed documentation for the Annotations API is available [here](https://buildkite.com/docs/apis/rest-api/annotations)

#### Get annotations uploaded by a build
```php
$buildNumber = 12;
$api->annotation()->list('my-organization', 'my-pipeline', $buildNumber);
```

### Users API

Users related methods are exposed via `$api->user()`

Detailed documentation for the Users API is available [here](https://buildkite.com/docs/apis/rest-api/users)

#### Get current user
```php
$api->user()->whoami();
```

### Emojis API

Emojis related methods are exposed via `$api->emoji()`

Detailed documentation for the Users API is available [here](https://buildkite.com/docs/apis/rest-api/emojis)

#### List available emojis
```php
$api->emoji()->list('my-organization');
```

## Contribution

### Testing

```shell script
make test
```

### Integration testing
A Buildkite account and a running agent is required for integration testing and the following environment variables must be set.

* `BK_TEST_TOKEN`
* `BK_TEST_ORG`
* `BK_TEST_PREFIX`
* `GITHUB_REF`
* `GITHUB_REPOSITORY`

These can be set in the `phpunit.xml` by making a copy of `phpunit.xml.dist` and extending it with the following snippet
```xml
    <php>
        <env name="BK_TEST_TOKEN" value="my-buildkite-api-token"/>
        <env name="BK_TEST_ORG" value="my-organization-slug"/>
        <env name="BK_TEST_PREFIX" value="something-uniqe"/>
        <env name="GITHUB_REF" value="refs/heads/master"/>
        <env name="GITHUB_REPOSITORY" value="your-name/buildkite-php"/>
    </php>
```

Once the environment variables are set the test suite can be started

```shell script
make integration
```
