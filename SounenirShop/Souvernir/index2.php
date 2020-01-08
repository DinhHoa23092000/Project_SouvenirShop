<!DOCTYPE html>
<html lang="en">
  <head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      *{
        padding:0;
        margin:0;
    }
    html{
        width:100%;    
        height: 100%;
    }
    body{
        background-color: #2980B9;
        font-family: 'Lato', sans-serif;
    }
    div.container{
        display: grid;
        grid-template-columns: auto auto auto auto;
        grid-gap: 10px;
        margin-left: 50px;
        margin-right: 50px;
    }
    img {
    width: 100%;
    height: auto;
    transition: all ease-in-out ;
}
 
.grid-item {
    width: 300px;
    height: 225px;
    position: relative;
    overflow: hidden;
}
 
.txt {
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.52);
    position: absolute;
    bottom:50px;
    text-align: center;
    color: white;
    padding:10px;
    box-sizing: border-box;
    opacity: 0;
}
.grid-item:hover div.txt {
    opacity: 1;
    transform: translateY(50px);
    transition:ease-in-out 0.5s;
}
 
.grid-item:hover img {
    transform: scale(1.5);
    transition: all ease-in-out 0.5s;
 
}
.buttons{
  display: flex;
  justify-content: space-between;
  align-content: center;
  align-items: center;
  margin-top: 120px;
  margin-left: 100px;
  margin-right: 100px;
}
 
    </style>
  </head>
  <body>
  <body>
    <div class="container">
      <?php 
      for($i=0; $i<4; $i++){
      ?>
        <div class="grid-item">
            <img src="http://hasinhayder.github.io/ImageCaptionHoverAnimation/img/chaps_1x.jpg" alt="">
            <div class="txt">
                <h3>AMAZING CAPTION</h3>
                <p>Whatever It Is - Always Awesome</p>
                <div class="buttons">
              <button>Detail</button>
              <button>Add</button>
            </div>
            </div>
           
        </div>
      <?php }?>
    </div>
</body>
</html>
 
  </body>
</html>