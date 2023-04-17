<?php

use App\Models\Tag;
use Illuminate\Database\Seeder;

class ServerTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * Server Games
         * 
         * @var array
         */
        $tags = [
            "Survival",
            "Creative",
            "Hardcore",
            "Adventure",
            "Vanilla",
            "CraftBukkit",
            "Spigot",
            "Roleplay",
            "PVP",
            "No PVP",
            "PVE",
            "No PVE",
            "Whitelist",
            "Hunger Games",
            "Survival Games",
            "Factions",
            "Hardcore Factions",
            "Capture the flag",
            "McMMO",
            "Economy",
            "Tekkit",
            "Skyblock",
            "FTB",
            "Pixelmon",
            "KitPVP",
            "Mini Games",
            "Prison",
            "Network",
        ];

        foreach($tags as $t){
            Tag::create(["name"=>$t]);
        }
    }
}
