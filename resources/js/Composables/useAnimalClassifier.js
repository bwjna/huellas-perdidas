import * as tf from '@tensorflow/tfjs'

let modelo = null
let cargandoPromesa = null

async function cargarModelo() {
  if (modelo) return modelo
  if (!cargandoPromesa) {
    cargandoPromesa = tf.loadLayersModel('/modelo/model.json')
  }
  modelo = await cargandoPromesa
  return modelo
}

export async function esImagenDeMascota(file, umbral = 0.6) {
  try {
    const modeloListo = await cargarModelo()
    const imgBitmap = await createImageBitmap(file)
    const tensor = tf.tidy(() => {
      return tf.browser.fromPixels(imgBitmap)
        .resizeBilinear([160, 160])
        .toFloat()
        .expandDims(0)
    })
    const salida = modeloListo.predict(tensor)
    const [probNoAnimal] = await salida.data()
    tensor.dispose()
    salida.dispose()
    const probAnimal = 1 - probNoAnimal
    return {
      esMascota: probAnimal >= umbral,
      confianza: probAnimal,
    }
  } catch (error) {
    console.error('Error en esImagenDeMascota:', error)  // <- temporal, para debug
    throw error
  }
}