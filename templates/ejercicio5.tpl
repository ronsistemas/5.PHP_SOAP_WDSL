<!DOCTYPE html>
<HTML>
    <HEAD>
        <title>
            Tarea 5 Ejercicio 5. Consultar una medición con SOAPClient.
        </title>
    </HEAD>
    <body>
        <H1>Tarea 5 Ejercicio 5. Consultar una medición con SOAPClient.</H1>
        <H3>Autor/a: profesor</H3>    
        
        {include file='operationresult.tpl'}          
        <form action="" method="post">
            <label> Día: <input type="text" name="day" value="{$day|default:''}"> </label><br>            
            <label> Mes: <input type="text" name="month" value="{$month|default:''}"> </label><br>            
            <label> Año: <input type="text" name="year" value="{$year|default:''}"> </label><br>            
            <label> Estación: <input type="text" name="estacion" value="{$estacion|default:''}"></label><br>          
            <input type="submit" value='Enviar!' name="btn">
        </form>
        {if isset($result) }      
            <H2>Resultados recibidos:</H2>
            <table border='1'>
                {foreach $result as $tram=>$val}
                <tr>
                    <th>Tramo {$tram|replace:'tramo':''}</th>
                    <td>
                        {if $val==-1} Error en los parámetros de la consulta. 
                        {elseif $val==-2} Error en la base de datos.
                        {else} {$val} {/if}
                    </td>                    
                </tr>
                {/foreach}
            </table>
        {/if}

    </body>
    
</HTML>
