<!DOCTYPE html>
<html>

<head>
  <title>Página de Productos</title>
  <link rel="stylesheet" type="text/css" href="css/Productos.css">
</head>

<body>
  <h1>Productos</h1>
  <form action="procesar_pedido.php" method="post">
    <div class="product-container">
      <div class="product">
        <img src="./Img/CafeAmericano.png" alt="Producto 1" height="170" width="170">
        <h2 id="nombre_producto">Café Molido Fino</h2>
        <input type="hidden" id="id_producto" name="id_producto">
        <p>Categoria: Americano</p>
        <p>Es un café tradicionalmente americano molido y fino</p>
        <label for="id_pedido">ID Pedido:</label>
        <input type="number" id="id_pedido" name="id_pedido"><br><br>
        <label for="id_cliente">ID Cliente:</label>
        <input type="number" id="id_cliente" name="id_cliente" required><br><br>

        <p>Precio: $5.99</p>
        <label for="cantidad_producto1">Cantidad:</label>
        <input type="number" id="cantidad_producto1" name="cantidad_producto1" min="1"><br><br>
        <button class="boton" type="submit" name="comprar_producto" value="Café Molido Fino">Comprar ahora</button>
      </div>
      <div class="product">
        <img src="./Img/Café Cold Brew.jpg" alt="Producto 2" height="170" width="170">
        <h2>Café Cold Brew</h2>
        <p>Categoria: Americano</p>
        <p>Es un café tradicionalmente americano infusionado y frio</p>
        <p>Precio: $5.99</p>
        <label for="cantidad_producto1">Cantidad:</label>
        <input type="number" id="cantidad_producto1" name="cantidad_producto1" min="1"><br><br>
        <button class="boton" type="submit">Comprar ahora</button>
      </div>
      <div class="product">
        <img src="./Img/CafePuerto.jpg" alt="Producto 3" height="170" width="170">
        <h2>Café Puertorriqueño Suave</h2>
        <p>Categoria: Puertorriqueño</p>
        <p>Es un café tradicionalmente puertorriqueño</p>
        <p>Precio: $5.99</p>
        <label for="cantidad_producto1">Cantidad:</label>
        <input type="number" id="cantidad_producto1" name="cantidad_producto1" min="1"><br><br>
        <button class="boton" type="submit">Comprar ahora</button>
      </div>
      <div class="product">
        <img src="./Img/CafeAmericano.png" alt="Producto 1">
        <h2>Café Molido Fino</h2>
        <p>Categoria: Americano</p>
        <p>Es un café tradicionalmente americano</p>
        <p>Precio: $5.99</p>
        <label for="cantidad_producto1">Cantidad:</label>
        <input type="number" id="cantidad_producto1" name="cantidad_producto1" min="1"><br><br>
        <button class="boton" type="submit">Comprar ahora</button>
      </div>
      <div class="product">
        <img src="./Img/CafeAmericano.png" alt="Producto 1">
        <h2>Café Molido Fino</h2>
        <p>Categoria: Americano</p>
        <p>Es un café tradicionalmente americano</p>
        <p>Precio: $5.99</p>
        <label for="cantidad_producto1">Cantidad:</label>
        <input type="number" id="cantidad_producto1" name="cantidad_producto1" min="1"><br><br>
        <button class="boton" type="submit">Comprar ahora</button>
      </div>
      <div class="product">
        <img src="./Img/CafeAmericano.png" alt="Producto 1">
        <h2>Café Molido Fino</h2>
        <p>Categoria: Americano</p>
        <p>Es un café tradicionalmente americano</p>
        <p>Precio: $5.99</p>
        <label for="cantidad_producto1">Cantidad:</label>
        <input type="number" id="cantidad_producto1" name="cantidad_producto1" min="1"><br><br>
        <button class="boton" type="submit">Comprar ahora</button>
      </div>
      <div class="product">
        <img src="./Img/CafeAmericano.png" alt="Producto 1">
        <h2>Café Molido Fino</h2>
        <p>Categoria: Americano</p>
        <p>Es un café tradicionalmente americano</p>
        <p>Precio: $5.99</p>
        <label for="cantidad_producto1">Cantidad:</label>
        <input type="number" id="cantidad_producto1" name="cantidad_producto1" min="1"><br><br>
        <button class="boton" type="submit">Comprar ahora</button>
      </div>
      <div class="product">
        <img src="./Img/CafeAmericano.png" alt="Producto 1">
        <h2>Café Molido Fino</h2>
        <p>Categoria: Americano</p>
        <p>Es un café tradicionalmente americano</p>
        <p>Precio: $5.99</p>
        <label for="cantidad_producto1">Cantidad:</label>
        <input type="number" id="cantidad_producto1" name="cantidad_producto1" min="1"><br><br>
        <button class="boton" type="submit">Comprar ahora</button>
      </div>
      <div class="product">
        <img src="./Img/CafeAmericano.png" alt="Producto 1">
        <h2>Café Molido Fino</h2>
        <p>Categoria: Americano</p>
        <p>Es un café tradicionalmente americano</p>
        <p>Precio: $5.99</p>
        <label for="cantidad_producto1">Cantidad:</label>
        <input type="number" id="cantidad_producto1" name="cantidad_producto1" min="1"><br><br>
        <button class="boton" type="submit">Comprar ahora</button>
      </div>
    </div>
  </form>

  <form action="Pedidos.php" method="post">
    <button class="boton" type="submit">Hacer pedido</button>
  </form>

  <script>
    function setProductId() {
      var nombreProducto = document.getElementById("nombre_producto").innerText;
      document.getElementById("id_producto").value = nombreProducto;
    }
  </script>
</body>

</html>