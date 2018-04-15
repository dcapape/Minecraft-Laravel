<?php

class euVATSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
    DB::table('euVAT')->delete();

    DB::table('euVAT')->insert(array('name' => 'Belgium', 'code' => 'BE', 'VAT' => 21));
    DB::table('euVAT')->insert(array('name' => 'Bulgaria', 'code' => 'BG', 'VAT' => 20));
    DB::table('euVAT')->insert(array('name' => 'Czech Republic', 'code' => 'CZ', 'VAT' => 21));
    DB::table('euVAT')->insert(array('name' => 'Denmark', 'code' => 'DK', 'VAT' => 25));
    DB::table('euVAT')->insert(array('name' => 'Germany', 'code' => 'DE', 'VAT' => 19));
    DB::table('euVAT')->insert(array('name' => 'Estonia', 'code' => 'EE', 'VAT' => 20));
    DB::table('euVAT')->insert(array('name' => 'Ireland', 'code' => 'IE', 'VAT' => 23));
    DB::table('euVAT')->insert(array('name' => 'Greece', 'code' => 'EL', 'VAT' => 24));
    DB::table('euVAT')->insert(array('name' => 'Spain', 'code' => 'ES', 'VAT' => 21));
    DB::table('euVAT')->insert(array('name' => 'France', 'code' => 'FR', 'VAT' => 20));
    DB::table('euVAT')->insert(array('name' => 'Croatia', 'code' => 'HR', 'VAT' => 25));
    DB::table('euVAT')->insert(array('name' => 'Italy', 'code' => 'IT', 'VAT' => 22));
    DB::table('euVAT')->insert(array('name' => 'Cyprus', 'code' => 'CY', 'VAT' => 19));
    DB::table('euVAT')->insert(array('name' => 'Latvia', 'code' => 'LV', 'VAT' => 21));
    DB::table('euVAT')->insert(array('name' => 'Lithuania', 'code' => 'LT', 'VAT' => 21));
    DB::table('euVAT')->insert(array('name' => 'Luxembourg', 'code' => 'LU', 'VAT' => 17));
    DB::table('euVAT')->insert(array('name' => 'Hungary', 'code' => 'HU', 'VAT' => 27));
    DB::table('euVAT')->insert(array('name' => 'Malta', 'code' => 'MT', 'VAT' => 18));
    DB::table('euVAT')->insert(array('name' => 'Netherlands', 'code' => 'NL', 'VAT' => 21));
    DB::table('euVAT')->insert(array('name' => 'Austria', 'code' => 'AT', 'VAT' => 20));
    DB::table('euVAT')->insert(array('name' => 'Poland', 'code' => 'PL', 'VAT' => 23));
    DB::table('euVAT')->insert(array('name' => 'Portugal', 'code' => 'PT', 'VAT' => 23));
    DB::table('euVAT')->insert(array('name' => 'Romania', 'code' => 'RO', 'VAT' => 19));
    DB::table('euVAT')->insert(array('name' => 'Slovenia', 'code' => 'SI', 'VAT' => 22));
    DB::table('euVAT')->insert(array('name' => 'Slovakia', 'code' => 'SK', 'VAT' => 20));
    DB::table('euVAT')->insert(array('name' => 'Finland', 'code' => 'FI', 'VAT' => 24));
    DB::table('euVAT')->insert(array('name' => 'Sweden', 'code' => 'SE', 'VAT' => 25));
    DB::table('euVAT')->insert(array('name' => 'United Kingdom', 'code' => 'UK', 'VAT' => 20));

	}

}
