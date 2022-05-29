<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Configs Controller
 *
 * @property \App\Model\Table\ConfigsTable $Configs
 */
class ConfigsController extends AppController
{
    /**
     * Edit method
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit()
    {
        $config = $this->Configs->find()->where([$this->Configs->aliasField('id') => 1])->first();
        if (empty($config)) {
            $config = $this->Configs->newEmptyEntity();
        }
        assert($config instanceof \App\Model\Entity\Config);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->getRequest()->getData());
            if ($config->hasErrors()) {
                $this->Flash->set(implode('<br />', $config->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm'],
                ]);
            } else {
                $conn = $this->Configs->getConnection();
                $conn->begin();
                if ($this->Configs->save($config, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('設定の登録が完了しました。');

                    return $this->redirect(['action' => 'edit']);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('config'));

        return $this->render('edit');
    }
}
