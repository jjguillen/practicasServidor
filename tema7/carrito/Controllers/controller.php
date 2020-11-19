<?php
    session_start();
    //session_destroy();

    include_once("..\autoload.php");
    use Carrodelacompra\VistaIndex;
    use Carrodelacompra\VistaCarro;
    use Carrodelacompra\CarroCompra;
    use Carrodelacompra\ProductoDB;
    use Carrodelacompra\LineaCarro;

    //Recuperamos el carro de la sesión
    if (!isset($_SESSION['carrito'])) {
        $carro = new CarroCompra();
        $_SESSION['carrito'] = serialize($carro);
    } else {
        $carro = unserialize($_SESSION['carrito']);
    }
    
    //Acciones desde JQuery
    if (isset($_GET['accion'])) {

        //Mostrar productos de la tienda
        if ($_GET['accion'] == 'mostrar') {
            //Recuperamos el carro de la sesión
            if (!isset($_SESSION['carrito'])) {
                $carro = new CarroCompra();
                $_SESSION['carrito'] = serialize($carro);
            } else {
                $carro = unserialize($_SESSION['carrito']);
            }

            //Recuperar los productos de la BD como objetos
            $productos = ProductoDB::getProductos();
            VistaIndex::render($productos,$carro->count());            
        }

        //Añadir producto al carro
        if ($_GET['accion'] == 'comprar') {      
            //Llamamos a BD para obtener el objeto producto con ese id
            $producto = ProductoDB::getProductoId($_GET['id']);
            //Creamos una línea de carro con ese producto
            $lineaCarro = new LineaCarro($producto);
            //Deserializamos el carro de la sesión para añadirle esa línea de carro
            $carro = unserialize($_SESSION['carrito']);
            //Añadimos al carro esa línea de carro
            $carro->add($lineaCarro);
            //Volvemos a serializar el carro con la nueva línea añadida
            $_SESSION['carrito'] = serialize($carro); 
            
            echo $carro->count();

        }    
        
        //Mostrar el carro
        if ($_GET['accion'] == 'mostrarCarro') {
            VistaCarro::render(unserialize($_SESSION['carrito']));

        }

        //Sumar/restar cantidad de un producto en el carro
        if ($_GET['accion'] == 'cambiar') {
            $id = $_GET['id'];
            //Deserializamos el carro de la sesión para añadirle esa línea de carro
            $carro = unserialize($_SESSION['carrito']);
            foreach($carro->getLineasCarro() as $linea) {
                if ($linea->getProducto()->getId() == $id ) {
                    if ($_GET['tipo'] == 1)
                        $linea->incrementarCantidad();
                    if ($_GET['tipo'] == 2)
                        $linea->decrementarCantidad();
                }   
            }

            //Volvemos a serializar el carro con la nueva línea añadida
            $_SESSION['carrito'] = serialize($carro);
            //Pintamos el carro
            VistaCarro::render($carro);
        }

       //Eliminar línea del carro
       if ($_GET['accion'] == 'deleteLinea') {
        $id = $_GET['id'];
        //Deserializamos el carro de la sesión para añadirle esa línea de carro
        $carro = unserialize($_SESSION['carrito']);
        $carro->eliminarLinea($id);

        //Volvemos a serializar el carro con la nueva línea añadida
        $_SESSION['carrito'] = serialize($carro);
        //Pintamos el carro
        VistaCarro::render($carro);

    }   
        
        

    }

    




?>