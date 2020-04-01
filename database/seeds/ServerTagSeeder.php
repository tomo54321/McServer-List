<?php

use App\Tag;
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
            "No PVE",
            "Whitelist",
            "Hunger Games",
            "Survival Games",
            "Factions",
            "Hardcore Factions",
            "CTF",
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
            "Hub",
            "Network",
            "FTop"
        ];

        foreach($tags as $t){
            Tag::create(["name"=>$t]);
        }
    }
}
