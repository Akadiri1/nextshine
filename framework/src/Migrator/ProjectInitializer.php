<?php
namespace App\Migrator;

class ProjectInitializer extends Initializer {
    
    public function setup() {
        echo "Initializing Custom Project Setup...\n";

        // 1. Wipe and Migrate
        $this->migrator->fresh();

        // 2. Create your specific tables
        $this->migrator->table('settings_website_info', function ($table) {
            $table->id();
            $table->string('visibility')->default('hide');
            $table->string('input_name')->nullable();
            $table->text('text_description')->nullable();
            $table->timestamps();
        });

        // 3. Call Seeder logic directly without external files
        echo "Injecting mandatory data via Inline Seeder...\n";
        
        // We create an anonymous class that extends Seeder to access upsert()
        $siteInfoSeeder = new class($this->pdo) extends Seeder {
            public function run() {
                $settings = [
                    [
                        'visibility' => 'show',
                        'input_name' => 'Mckode Framework',
                        'text_description' => 'Mckodev PHP framework.',
                        'time_created' => date('Y-m-d H:i:s'),
                        'date_created' => date('Y-m-d H:i:s'),
                    ],

                ];

                foreach ($settings as $row) {
                    // This uses the industry-standard 'upsert' logic we built
                    $this->upsert('settings_website_info', $row, 'id');
                }
            }
        };

        $siteInfoSeeder->run();

        // 4. Optionally run any actual seeder files if they exist
        // Seeder::runAll($this->pdo);

        echo "Initialization complete!\n";
    }
}