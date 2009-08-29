<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginsfAuthCredentialTable extends Doctrine_Table
{
    public function getAllByUser(sfAuthUser $user)
    {       
        $q = $this->createQuery('c');
        $q->leftjoin('c.sfAuthUserCredential uc');
        $q->leftjoin('c.sfAuthGroup g');
        $q->leftjoin('g.sfAuthGroupCredential cg');
        $q->leftjoin('cg.sfAuthGroup cgg');
        $q->leftjoin('cgg.sfAuthUserGroup ugg');
        $q->addWhere('ugg.sf_auth_user_id = ? OR uc.sf_auth_user_id = ?', array($user->getId(), $user->getId()));
        $q->groupby('c.id');
        
        //Aggregate
        $credentials = array();
        foreach ($q->execute(array(), Doctrine::HYDRATE_ARRAY) as $credential) {
            $credentials[] = $credential['name'];
        }
        
        return $credentials;
    }
}