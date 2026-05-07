<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        $sampleQuestions = [
            [
                'question_text' => 'AWS S3 is primarily what type of service?',
                'option_a' => 'Compute service',
                'option_b' => 'Object storage service',
                'option_c' => 'Relational database service',
                'option_d' => 'DNS service',
                'correct_option' => 'b',
                'category' => 'AWS',
                'explanation' => 'S3 is AWS managed object storage.',
            ],
            [
                'question_text' => 'In Azure, which service provides managed Kubernetes?',
                'option_a' => 'Azure Functions',
                'option_b' => 'Azure SQL Database',
                'option_c' => 'Azure Kubernetes Service (AKS)',
                'option_d' => 'Azure App Service',
                'correct_option' => 'c',
                'category' => 'Azure',
                'explanation' => 'AKS is Azure managed Kubernetes.',
            ],
            [
                'question_text' => 'Which GCP service is equivalent to AWS EC2 virtual machines?',
                'option_a' => 'Cloud Run',
                'option_b' => 'Compute Engine',
                'option_c' => 'Cloud Functions',
                'option_d' => 'BigQuery',
                'correct_option' => 'b',
                'category' => 'GCP',
                'explanation' => 'Compute Engine provides VM instances in Google Cloud.',
            ],
            [
                'question_text' => 'What is the main benefit of Infrastructure as Code (IaC)?',
                'option_a' => 'Higher cloud storage limits',
                'option_b' => 'Manual server patching',
                'option_c' => 'Repeatable and versioned infrastructure provisioning',
                'option_d' => 'Faster internet connectivity',
                'correct_option' => 'c',
                'category' => 'DevOps',
                'explanation' => 'IaC enables repeatability, automation, and version control for infrastructure.',
            ],
            [
                'question_text' => 'Which AWS service is used for identity and access management?',
                'option_a' => 'IAM',
                'option_b' => 'CloudTrail',
                'option_c' => 'Kinesis',
                'option_d' => 'SQS',
                'correct_option' => 'a',
                'category' => 'Security',
                'explanation' => 'IAM controls authentication and authorization in AWS.',
            ],
            [
                'question_text' => 'In Azure, what does VNet stand for?',
                'option_a' => 'Virtual Network',
                'option_b' => 'Verified Node',
                'option_c' => 'Virtual Namespace',
                'option_d' => 'Volume Network',
                'correct_option' => 'a',
                'category' => 'Networking',
                'explanation' => 'VNet is Azure Virtual Network service.',
            ],
            [
                'question_text' => 'Which database service is fully managed and globally distributed in Azure?',
                'option_a' => 'Azure Cosmos DB',
                'option_b' => 'Azure Files',
                'option_c' => 'Azure Data Box',
                'option_d' => 'Azure DevOps',
                'correct_option' => 'a',
                'category' => 'Azure',
                'explanation' => 'Cosmos DB is globally distributed and fully managed.',
            ],
            [
                'question_text' => 'Which AWS service provides serverless event-driven compute?',
                'option_a' => 'Elastic Beanstalk',
                'option_b' => 'AWS Lambda',
                'option_c' => 'Amazon Redshift',
                'option_d' => 'Amazon EMR',
                'correct_option' => 'b',
                'category' => 'AWS',
                'explanation' => 'AWS Lambda runs functions without managing servers.',
            ],
            [
                'question_text' => 'What is the purpose of a cloud load balancer?',
                'option_a' => 'Encrypt local files',
                'option_b' => 'Distribute traffic across multiple targets',
                'option_c' => 'Create user passwords',
                'option_d' => 'Store DNS records only',
                'correct_option' => 'b',
                'category' => 'Networking',
                'explanation' => 'Load balancers improve availability by distributing requests.',
            ],
            [
                'question_text' => 'Which service is commonly used for container orchestration?',
                'option_a' => 'Kubernetes',
                'option_b' => 'FTP',
                'option_c' => 'LDAP',
                'option_d' => 'SMTP',
                'correct_option' => 'a',
                'category' => 'Containers',
                'explanation' => 'Kubernetes orchestrates deployment, scaling, and operations of containers.',
            ],
            [
                'question_text' => 'In cloud security, what does MFA stand for?',
                'option_a' => 'Managed File Access',
                'option_b' => 'Multi-Factor Authentication',
                'option_c' => 'Main Function API',
                'option_d' => 'Manual Firewall Assignment',
                'correct_option' => 'b',
                'category' => 'Security',
                'explanation' => 'MFA adds additional verification factors beyond password.',
            ],
            [
                'question_text' => 'Which cloud model offers hardware resources dedicated to a single organization?',
                'option_a' => 'Public cloud',
                'option_b' => 'Private cloud',
                'option_c' => 'Community cloud',
                'option_d' => 'Multi-tenant cloud',
                'correct_option' => 'b',
                'category' => 'Cloud Fundamentals',
                'explanation' => 'Private cloud provides dedicated infrastructure for one organization.',
            ],
        ];

        foreach ($sampleQuestions as $question) {
            Question::query()->updateOrCreate(
                ['question_text' => $question['question_text']],
                [
                    'option_a' => $question['option_a'],
                    'option_b' => $question['option_b'],
                    'option_c' => $question['option_c'],
                    'option_d' => $question['option_d'],
                    'correct_option' => $question['correct_option'],
                    'category' => $question['category'],
                    'is_active' => true,
                    'explanation' => $question['explanation'],
                ]
            );
        }
    }
}
