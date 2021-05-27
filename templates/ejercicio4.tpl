<!DOCTYPE html>
<HTML>
    <HEAD>
        <title>
            Tarea 5 Ejercicio 4. Registrar una medición con SOAPClient.
        </title>
    </HEAD>
    <body>
        <H1>Tarea 5 Ejercicio 4. Registrar una medición con SOAPClient.</H1>
        <H3>Autor/a: profesor</H3>    
        
        {include file='operationresult.tpl'}
        
        <form action="" method="post">
            <label>Fecha: <input type="text" name="fecha" value='{$fecha|default:''}'> (formato dd/mm/aaaa)</label><br>
            {$trm=$tramo|default:''|replace:'tramo':''}            
            <label>Tramo: <select name="tramo"> 
                    {for $var=1 to 4}
                        <option value="tramo{$var}" {if $trm==$var}selected{/if}>Tramo {$var}</option>                    
                    {/for}
                </select></label><br>
            <label> Estación: <input type="text" name="estacion" value='{$estacion|default:''}'></label><br>
            <label> Recuento: <input type="text" name="recuento" value='{$recuento|default:''}'></label><br>
            <input type="submit" value='Enviar!' name="btn_new">
        </form>

    </body>
    
</HTML>
