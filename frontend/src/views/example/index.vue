<template>
  <div
    v-loading="loading"
    class="primary-block"
  >
    <div class="primary-block__header">
      <div class="primary-block__header-title">
        Закрытие дорог для грузового транспорта на просушку
      </div>
      <div class="primary-block__setting">
        <i class="el-icon-s-tools" />
      </div>
    </div>
    <div class="primary-block__body">
      <div>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aliquam amet aspernatur delectus ea ex excepturi fugiat fugit harum, laborum magnam maiores, natus nisi perspiciatis quasi, quibusdam similique unde velit! Consectetur dicta dolore ex exercitationem fugit hic in, incidunt labore laborum quaerat quis similique, sint! Accusantium asperiores blanditiis consectetur distinctio doloremque eius fugit laborum laudantium nostrum, officia reiciendis rerum similique sint veniam voluptatum. Ab asperiores aspernatur commodi culpa dolorum ducimus ea eius esse eum explicabo fugiat iure laboriosam libero molestias neque numquam obcaecati officia officiis omnis placeat quaerat quasi qui quisquam quod quos recusandae rem repellat, rerum saepe sit sunt tenetur vero voluptatem! Architecto deleniti dolorum exercitationem maxime quod totam. At enim exercitationem numquam odit, quasi recusandae sequi. Accusantium architecto aspernatur corporis dignissimos doloremque, eligendi explicabo illum ipsum laborum, laudantium minima necessitatibus nemo odit quasi quidem quod repellat sequi similique, temporibus voluptatum! Culpa error et explicabo, iste laboriosam maxime neque nostrum perspiciatis reprehenderit voluptas! Alias atque commodi dicta, ex exercitationem facere harum laudantium nisi omnis pariatur perferendis possimus quos sapiente, sequi soluta tempora voluptas. Autem beatae, cupiditate delectus dignissimos ea, eaque enim fugiat laudantium non, officiis provident quam sequi voluptatem? Aliquam dolorum error id, incidunt neque nobis nostrum sint tempora!
      </div>
    </div>
    <div class="primary-block__files">
      <div class="primary-block__files-header">Файлы:</div>
      <ul>
        <li> название файла
          <a href="#">
            Скачать {{ 4154534 | sizeFilter }}
          </a>
        </li>
        <li> sdfsdf sd dv zv
          <a href="#">
            Скачать {{ 4135434 | sizeFilter }}
          </a>
        </li>
      </ul>
    </div>
    <div class="primary-block__footer">
      <div class="primary-block__footer-back">
        <el-button type="primary" size="mini">Назад</el-button>
      </div>
      <div class="primary-block__footer-comment">
        <el-button type="primary" size="mini" plain icon="el-icon-chat-dot-square">25</el-button>
      </div>
      <div class="primary-block__footer-date">
        26 янв.
      </div>
    </div>
    <div class="primary-block__comment">
      <Comments v-model="article" />
    </div>
  </div>
</template>

<script>
import Comments from '@/components/Comments/index.vue'
export default {
  components: { Comments },
  data() {
    return {
      loading: false,
      article: {
        comments_show: [
          {
            id: 1,
            userName: 'Петров',
            created_at: '2021-01-05 10:10:10',
            message: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aliquam amet aspernatur delectus ea ex excepturi fugia',
            avatar: 'https://randomuser.me/api/portraits/women/' + Math.floor(Math.random() * 100) + '.jpg'
          },
          {
            id: 2,
            userName: 'Петров',
            created_at: '2021-01-05 10:10:10',
            message: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aliquam amet aspernatur delectus ea ex excepturi fugia',
            avatar: 'https://randomuser.me/api/portraits/women/' + Math.floor(Math.random() * 100) + '.jpg'
          },
          {
            id: 3,
            userName: 'Петров',
            created_at: '2021-01-05 10:10:10',
            message: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aliquam amet aspernatur delectus ea ex excepturi fugia',
            avatar: 'https://randomuser.me/api/portraits/women/' + Math.floor(Math.random() * 100) + '.jpg'
          },
          {
            id: 4,
            userName: 'Петров',
            created_at: '2021-01-05 10:10:10',
            message: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aliquam amet aspernatur delectus ea ex excepturi fugia',
            avatar: 'https://randomuser.me/api/portraits/women/' + Math.floor(Math.random() * 100) + '.jpg'
          },
          {
            id: 5,
            userName: 'Петров',
            created_at: '2021-01-05 10:10:10',
            message: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aliquam amet aspernatur delectus ea ex excepturi fugia',
            avatar: 'https://randomuser.me/api/portraits/women/' + Math.floor(Math.random() * 100) + '.jpg'
          }
        ]
      }
    }
  }
}
</script>

<style scoped>

</style>
