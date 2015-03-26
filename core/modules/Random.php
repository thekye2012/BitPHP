<?php

	//By m4s73r
	class Random
	{
		/**
	 	* genRandomChain([lenght])
		 * ========================
		 *
		 * Creara una cadena de n numero de caracteres (se indican en el parametro) con ([A-Z])[[a-z]]([0-9]) y ([_])
		 *
		 * @param int $lenght *numero de caracteres aleatorios que se colocaran el la cadena*
	 	*
		 * @return string     *cadena aleatoria de n numero de caracteres*
		 */
		public static function string( $length )
		{
			$pool    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012345678901234567899_';

			$limit = (strlen($pool) - 1); //limite que tenemos para seleccionar un caracter, la medida menos uno, los indices empiezan de '0' ;)
			$out = ''; //inicializamos la salida

			for ($i = 1;$i <= $length; $i++) //Para crear la cadena segun a medida indicada en el parametro
			{
				$out .= $pool[rand(0,$limit)]; //Seleccionamos un caracter indicando un indice aleatorio para $pool[]
			}

			return $out;
		}
		// genRandomChain ends
	}
?>