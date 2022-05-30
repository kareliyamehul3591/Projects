import React from 'react'
import works1 from '../../assets/images/works1.jpg';
import works2 from '../../assets/images/works2.jpg';
import works3 from '../../assets/images/works3.jpg';
import Header from '../Layout/Header';

const Philanthropy = () => {
    <Header />
    return (
    <div><section class="breadcrumb-box">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-auto">
                <h1>PHILANTHROPY</h1>
            </div>
            {/* <!-- <div class="col-12 col-sm">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">HOW IT WORKS</li>
                    </ol>
                </nav>
            </div> --> */}
        </div>
    </div>
</section>

<section>
    <div class="container section-padding">

        <div class="row flex-column-reverse flex-sm-row">
            <div class="col-12 div col-sm-6 col-md-6">
                <h2 class="title mb-4">PHILANTHROPY 1</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>

            <div class="col-12 div col-sm-6 col-md-6 mb-4 mb-sm-0">
                <img src={works1} class="radius-shadow-img img-fluid" alt="" />
            </div>


        </div>
    </div>
</section>

<section style={{backgroundColor: '#FFF0DF'}}>
    <div class="container section-padding">

        <div class="row">

            <div class="col-12 div col-sm-6 col-md-6 mb-4 mb-sm-0">
                <img src={works2} class="radius-shadow-img img-fluid" alt="" />
            </div>

            <div class="col-12 div col-sm-6 col-md-6">
                <h2 class="title mb-4">
                PHILANTHROPY 2
                </h2>
                <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero. </p>
                <p class="mb-0">Written in 45 BC. This book is a treatise on the theory of ethics.</p>
            </div>

        </div>
    </div>
</section>

<section>
    <div class="container section-padding">

        <div class="row flex-column-reverse flex-sm-row">
            <div class="col-12 div col-sm-6 col-md-6">
                <h2 class="title mb-4">
                PHILANTHROPY 3
                </h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>

            <div class="col-12 div col-sm-6 col-md-6 mb-4 mb-sm-0">
                <img src={works3} class="radius-shadow-img img-fluid" alt="" />
            </div>


        </div>
    </div>
</section>

<section style={{backgroundColor: '#FFF0DF'}}>
    <div class="container section-padding">

        <div class="row">

            <div class="col-12 div col-sm-6 col-md-6 mb-4 mb-sm-0">
                <img src={works2} class="radius-shadow-img img-fluid" alt="" />
            </div>

            <div class="col-12 div col-sm-6 col-md-6">
                <h2 class="title mb-4">
                PHILANTHROPY 4
                </h2>
                <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero. </p>
                <p class="mb-0">Written in 45 BC. This book is a treatise on the theory of ethics.</p>
            </div>

        </div>
    </div>
</section></div>
  )
}

export default Philanthropy;